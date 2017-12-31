<?php

namespace App;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'stt';
    protected $query;
    protected $table = 'documents';
    protected $column_order = [null, 'documents.id','documents.title','documents.updated_at']; //set column field database for datatable orderable
    protected $column_search = ['documents.title']; //set column field database for datatable searchable 
    protected $order = ['documents.id' => 'asc']; // default order 

    private function _get_datatables_query()
    {
        $this->query = DB::table($this->table);
        $this->query->join('categories', 'categories.id', '=', 'documents.category_id');
        $this->query->select('documents.stt', 'documents.id', 'documents.title','documents.updated_at');
        $this->query->where('categories.searchable', 1);

        if (Input::get('cat'))
            $this->query->where('documents.category_id', Input::get('cat'));

         // loop column 
        foreach ($this->column_search as $item) {
            if(Input::has('search.value')) {
                //remove duplicate white space
                $search = preg_replace('/\s\s+/', ' ', Input::get('search.value'));
                $search = rtrim($search);
                // Quote regular expression characters
                $search = preg_quote($search);
                // count " in string
                $cQuote = preg_match_all('/"/', $search);
                // check if exits two of " or '
                if ($cQuote > 1) {
                    $sQuote = '"';
                } else {
                    $cQuote = preg_match_all("/'/", $search);
                    if ($cQuote > 1)
                        $sQuote = "'";
                }
                // only search text between " " or ' '
                if ($cQuote > 1) {
                    $posBegin = strpos($search, $sQuote) + 1;
                    $posEnd   = strpos($search, $sQuote, $posBegin);
                    $length   = $posEnd - $posBegin;
                    $search   = substr($search, $posBegin, $length);
                    // remove " and '
                    $search = preg_replace('/"/', '', $search);
                    $search = preg_replace("/'/", '', $search);
                    $this->query->where($item, 'like', '%'.$search.'%');
                    Input::merge(['sSearch' => $search]);
                    continue;
                }
                // remove " and '
                $search = preg_replace('/"/', '', $search);
                $search = preg_replace("/'/", '', $search);
                // creat new input to use when highlight
                Input::merge(['sSearch' => $search]);

                $this->query->where( function($q) use($item, $search) {
                    // searching whole string
                    $q->where($item, 'like', '%'.$search.'%');
                    // then each part of string
                    if (strpos($search, " ")) {
                        //searching 1 by 2
                        $strs      = explode(" ", $search);
                        $strsCount = count($strs)/2 + 1;
                        for ($j = 0; $j < $strsCount; $j += 2) {
                            $subStr = $strs[$j];
                            if (isset($strs[$j + 1])) {
                                $subStr = $subStr." ".$strs[$j + 1];
                            }
                            $q->orWhere($item, 'like', '%'.$subStr.'%');
                        }
                    }
                });

            }
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
        // when not use searching
        if (!Input::has('search.value')) {
            $this->query->offset(Input::get('start'));
            $this->query->limit(Input::get('length'));
        }
        // get all match result
        $result = $this->query->get();

        if (empty($result)) return $result;

        //highlight matching result
        if (Input::has('search.value')) {
            $full      = $part = [];
            $numResult = count($result);
            $search    = Input::get('sSearch');

            for ($i = 0; $i < $numResult; $i++) {
                $result[$i]->title = preg_replace(
                    "/\p{L}*?".$search."\p{L}*/ui",
                    "<b style='background-color:yellow;'>$0</b>",
                    $result[$i]->title
                );
                // add "highlight all text" to $full array
                if (strpos($result[$i]->title, "</b") > 0) {
                    $full[] = $result[$i];
                    continue;
                }
                // add "highlight each part" to $part array
                if (strpos($search, " ") && (substr_count($search, ' ') >= 2)) {
                    $strs = explode(" ", $search);
                    $strsCount = count($strs)/2 + 1;
                    for ($j = 0; $j < $strsCount; $j += 2) {
                        $subStr = $strs[$j];
                        if (isset($strs[$j + 1])) {
                            $subStr = $subStr." ".$strs[$j + 1];
                        }
                        $result[$i]->title = preg_replace(
                            "/\p{L}*?".$subStr."\p{L}*/ui",
                            "<b style='background-color:yellow;'>$0</b>",
                            $result[$i]->title
                        );
                        $part[] = $result[$i];
                    }
                }
            }
            $result = array_merge($full, $part);
            // paging by cutting array
            $end = Input::get('start') + Input::get('length');
            $tmp = [];
            for ($i = Input::get('start'); $i < $end; $i++) {
                if (isset($result[$i])) {
                    $tmp[] = $result[$i];
                }
            }
            return $tmp;
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
        return $this->belongsTo('App\Category', 'category_id');
    }
}