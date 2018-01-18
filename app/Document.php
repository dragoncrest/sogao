<?php

namespace App;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'stt';
    protected $query;
    protected $table = 'documents';
    protected $column_order = [null, 'documents.id','documents.title','documents.updated_at']; //set column field database for datatable orderable
    protected $column_search = ['documents.title']; //set column field database for datatable searchable 
    protected $order = ['documents.stt' => 'asc']; // default order 

    private function _get_datatables_query()
    {
        $this->query = DB::table($this->table);
        $this->query->join('categories', 'categories.id', '=', 'documents.category_id');
        $this->query->select('documents.stt', 'documents.id', 'documents.title','documents.updated_at');

        if (strpos(URL::current(), 'admin') < 0) {
            $this->query->where('categories.searchable', 1);
        }

        if (Input::get('cat'))
            $this->query->where('documents.category_id', Input::get('cat'));

         // loop column 
        foreach ($this->column_search as $item) {
            if(Input::has('search.value')) {
                $search = $this->_processText(Input::get('search.value'));
                $this->query->where( function($q) use($item, $search) {
                    // searching whole word
                    $q->where($item, 'like', '%'.$search.'%');
                    // searching each part of word. For example:
                    // 'thu tuc lap' will be cut to: thu tuc - thu
                    $words    = explode(' ', $search);
                    $wordNumb = count($words);
                    for ($i = $wordNumb; $i > 0; $i--) {
                        unset($words[$i - 1]);
                        $str = implode(' ', $words);
                        if ($str) {
                            $q->orWhere($item, 'like', '%'.$str.'%');
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
        } else if (isset($this->order)) {
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
            $full      = $part = $partTmp = [];
            $numResult = count($result);
            $search    = Input::get('sSearch');
            $words     = explode(' ', $search);
            $wordNumb  = count($words);

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
                // searching each part of word. For example:
                // 'thu tuc lap' will be cut to: thu tuc - thu
                $wordTmp = $words;
                for ($j = $wordNumb; $j > 0; $j--) {
                    if (!strpos($result[$i]->title, "yellow")) {
                        unset($wordTmp[$j - 1]);
                        $subStr = implode(' ', $wordTmp);
                        if (!$subStr) continue;
                        // check if searched string is exits in title
                        $pos = stripos($result[$i]->title, $subStr);
                        if (is_numeric($pos)) {
                            $result[$i]->title = preg_replace(
                                "/\p{L}*?".$subStr."\p{L}*/ui",
                                "<b style='background-color:yellow;'>$0</b>",
                                $result[$i]->title
                            );
                            $part[$j - 1][] = $result[$i];
                        }
                    }
                }
            }

            for ($j = $wordNumb; $j > 0; $j--) {
                if (isset($part[$j - 1])) {
                    foreach ($part[$j - 1] as $value) {
                        $partTmp[] = $value;
                    }
                }
            }
            $result = array_merge($full, $partTmp);
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

    private function _processText ($str)
    {
        //remove duplicate white space
        $search = preg_replace('/\s\s+/', ' ', $str);
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

        return $search;
    }

}