<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $primaryKey = 'stt';
    
    protected $query;
    protected $table = 'ti_document';
    protected $column_order = array(null, 'id','title','updated_at'); //set column field database for datatable orderable
    protected $column_search = array('id','title'); //set column field database for datatable searchable 
    protected $order = array('id' => 'asc'); // default order 

    private function _get_datatables_query()
    {
         
        $this->query = DB::table($this->table);
        
        $catID = $_GET['cat'];
        if($catID>0)
            $this->query = $this->query->where('category', $_GET['cat']);
 
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                   // $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->query->where($item, 'like', '%'.$_GET['search']['value'].'%');
                }
                else
                {
                    $this->query->orWhere($item, 'like', '%'.$_GET['search']['value'].'%');
                }
 
                //if(count($this->column_search) - 1 == $i) //last loop
                    //$this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_GET['order'])) // here order processing
        {
            $this->query->orderBy($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->query->orderBy(key($order), $order[key($order)]);
        }
    }
 
    public function get_datatables()
    {
        $this->_get_datatables_query();
        
        if($_GET['length'] != -1){
            $this->query->offset($_GET['start']);
            $this->query->limit($_GET['length']);
        }
        
        return $this->query->get();
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
             
}
