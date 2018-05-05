<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Input;
use Validator;

class QuestionAnswer extends Model
{
    protected $guarded = [];
    protected $query;
    protected $table = 'question_answers';
    protected $column_order = []; //set column field database for datatable orderable
    protected $column_search = []; //set column field database for datatable searchable 
    protected $order = []; // default order

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->column_order = [
            $this->getTable() . '.title',
            $this->getTable() . '.email',
            $this->getTable() . '.status',
            $this->getTable() . '.created_at',
          ];
        $this->column_search = [$this->getTable() . '.title'];
        $this->order = [$this->getTable() . '.created_at' => 'desc'];
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_GET['length'] != -1) {
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
        return DB::table($this->getTable())->count();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    =>'email|max:255',
            'title'    => 'required|max:255',
            'question' => 'required',
        ]);
    }

    private function _get_datatables_query()
    {
        $this->query = DB::table($this->getTable());
        $this->query->select(
            $this->getTable() . '.id',
            $this->getTable() . '.title',
            $this->getTable() . '.email',
            $this->getTable() . '.status',
            $this->getTable() . '.created_at'
        );

        if (Input::get('catId')) {
            $this->query->where($this->getTable() . '.category_id', Input::get('catId'));
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if($_GET['search']['value']) {
                if ($i===0) {
                    $this->query->where($item, 'like', '%'.$_GET['search']['value'].'%');
                } else {
                    $this->query->orWhere($item, 'like', '%'.$_GET['search']['value'].'%');
                }
            }
            $i++;
        }

        if(isset($_GET['order'])) {
            $this->query->orderBy($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->query->orderBy(key($order), $order[key($order)]);
        }
    }
}
