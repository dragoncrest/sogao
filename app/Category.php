<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = FALSE;

    protected $query;
    protected $table = 'categories';
    protected $column_order = array(null, 'id','title'); //set column field database for datatable orderable
    protected $column_search = array('title'); //set column field database for datatable searchable 
    protected $order = array('id' => 'asc'); // default order 

    private function _get_datatables_query()
    {
         
        $this->query = DB::table($this->table);
 
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->query->where($item, 'like', '%'.$_GET['search']['value'].'%');
                }
                else
                {
                    $this->query->orWhere($item, 'like', '%'.$_GET['search']['value'].'%');
                }
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

    public function documents()
    {
        return $this->hasMany('App\Document');
    }
}
