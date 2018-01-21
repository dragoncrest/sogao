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

    private $count_filtered = 0;        //store number of matching document
    private $sSearch        = '';    //store separate of search string

    private function _getDatatablesQuery()
    {
        $this->query = DB::table($this->table);
        $this->query->join('categories', 'categories.id', '=', 'documents.category_id');
        $this->query->select('documents.stt', 'documents.id', 'documents.title','documents.updated_at');

        if (strpos(URL::current(), 'admin') < 0) {
            $this->query->where('categories.searchable', 1);
        }

        if (Input::get('cat'))
            $this->query->where('documents.category_id', Input::get('cat'));

        if(Input::has('search.value')) {
            $this->_getSearchStringQuery();
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
        $this->_getDatatablesQuery();
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
            return $this->_highlightMatchingWord($result);
        }
        return $result;
    }

    public function count_filtered()
    {
        if ($this->count_filtered) {
            $numb = $this->count_filtered;
        } else {
            $this->_getDatatablesQuery();
            $numb = $this->query->count();
        }
        return $numb;
    }

    public function count_all()
    {
        return DB::table($this->table)->count();
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    /**
    * create searching query with each part of search string
    */
    private function _getSearchStringQuery()
    {
        $words = [];
        //split string depend on quote
        preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', Input::get('search.value'), $matches);
        if (!empty($matches[0])) {
            //remove quote in each part
            foreach ($matches[0] as $key => $word) {
                $words[$key] = preg_replace('/"|\'/', '', $word);
                if (empty($words[$key])) {
                    unset($words[$key]);
                }
            }
            //create query with each search column
            foreach ($this->column_search as $item) {
                // create query with each part of search string
                $this->query->where( function($q) use($item, $words) {
                    $q->where($item, 'like', '%'.$words[0].'%');
                    $wordNumb = count($words);
                    for ($i = 1; $i < $wordNumb; $i++) {
                        $q->orWhere($item, 'like', '%'.$words[$i].'%');
                    }
                });
            }
        }
        // store to hightligh matching word in title of document
        $this->sSearch = $words;
    }

    /**
    * hightligh matching word in title of document
    * order from hight to low matching each part of search string
    * @param array $datas document
    * @return array of document with highlight title
    */
    private function _highlightMatchingWord($datas)
    {
        $numResult = count($datas);
        for ($i = 0; $i < $numResult; $i++) {
            //store how much word matching
            $match = 0;
            //searching each word in title
            foreach ($this->sSearch as $word) {
                $pos = mb_stripos($datas[$i]->title, $word);
                if (is_numeric($pos) && (strlen($word) > 1)) {
                    $word = mb_strtolower($word);
                    $datas[$i]->title = mb_strtolower($datas[$i]->title);
                    $datas[$i]->title = str_ireplace(
                        $word,
                        "<b style='background-color:#ffc107;'>$word</b>",
                        $datas[$i]->title
                    );
                    $match++;
                }
            }
            //push to each array depend on how much word matching
            if ($match) {
                $part[$match][] = $datas[$i];
            }
        }
        //merge all document ordered by matching number form hight to low
        $wordNumb = count($this->sSearch);
        $partTmp = [];
        for ($j = $wordNumb; $j > 0; $j--) {
            if (isset($part[$j])) {
                foreach ($part[$j] as $value) {
                    $partTmp[] = $value;
                }
            }
        }
        // store total of matching title
        $this->count_filtered = count($partTmp);
        // paging by cutting array
        $end = Input::get('start') + Input::get('length');
        $tmp = [];
        for ($i = Input::get('start'); $i < $end; $i++) {
            if (isset($partTmp[$i])) {
                $tmp[] = $partTmp[$i];
            }
        }
        return $tmp;
    }
}