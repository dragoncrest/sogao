<?php

namespace App;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'stt';
    
    protected $query;
    protected $table = 'ti_document';
    protected $column_order = [null, 'id','title','updated_at']; //set column field database for datatable orderable
    protected $column_search = ['id','title']; //set column field database for datatable searchable 
    protected $order = ['id' => 'asc']; // default order 

    private function _get_datatables_query()
    {
         
        $this->query = DB::table($this->table);
        
        $catID = $_GET['cat'];
        if ($catID>0)
            $this->query->where('category', $_GET['cat']);

        $i = 0;
         // loop column 
        foreach ($this->column_search as $item) {
            if($_GET['search']['value']) // if datatable send POST for search
            {
                $search = preg_replace('/\s\s+/', ' ', $_GET['search']['value']); //remove duplicate white space
                $search = rtrim($search);
                if ($i === 0) { // first loop
                   // $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->query->where($item, 'like', '%'.$search.'%');   //searching whole string
                } else {
                    if (strpos($search, " ")) {
                        //searching 2 by 1
                        $strs = explode(" ", $search);
                        $strsCount = count($strs)/2 + 1;
                        for ($j = 0; $j < $strsCount; $j += 2) {
                            $subStr = $strs[$j];
                            if (isset($strs[$j + 1])) {
                                $subStr = $subStr." ".$strs[$j + 1];
                            }
                            $this->query->orWhere($item, 'like', '%'.$subStr.'%');
                        }
                    } else {
                        $this->query->orWhere($item, 'like', '%'.$search.'%');
                    }
                }
                //if(count($this->column_search) - 1 == $i) //last loop
                    //$this->db->group_end(); //close bracket
            }
            $i++;
        }
        // here order processing
        if (isset($_GET['order'])) {
            $this->query->orderBy(
                $this->column_order[$_GET['order']['0']['column']],
                $_GET['order']['0']['dir']
            );
        } 
        else if(isset($this->order)) {
            $order = $this->order;
            $this->query->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        
        if ($_GET['length'] != -1) {
            $this->query->offset($_GET['start']);
            $this->query->limit($_GET['length']);
        }
        
        $result = $this->query->get();
        //highlight result
        if (Input::has('search.value')) {
            $numResult = count($result);
            for ($i = 0; $i < $numResult; $i++) {
                $search = preg_replace('/\s\s+/', ' ', Input::get('search.value')); //remove duplicate white space
                $search = rtrim($search);
                $result[$i]->title = preg_replace(
                    "/\p{L}*?".preg_quote($search)."\p{L}*/ui",
                    "<b style='background-color:yellow;'>$0</b>",
                    $result[$i]->title
                );

                if (strpos($result[$i]->title, "<b")) continue; //not highlight if it is already highlighted
                if (strpos($search, " ") && (substr_count($search, ' ') >= 2)) {
                    $strs = explode(" ", $search);
                    $strsCount = count($strs)/2 + 1;
                    for ($j = 0; $j < $strsCount; $j += 2) {
                        $subStr = $strs[$j];
                        if (isset($strs[$j + 1])) {
                            $subStr = $subStr." ".$strs[$j + 1];
                        }
                        $result[$i]->title = preg_replace(
                            "/\p{L}*?".preg_quote($subStr)."\p{L}*/ui",
                            "<b style='background-color:yellow;'>$0</b>",
                            $result[$i]->title
                        );
                    }
                }
            }
        }
        return $result;
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        
        return $this->query->count();
    }

    public function count_all()
    {
        return DB::table($this->table)->count();
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category');
    }
}