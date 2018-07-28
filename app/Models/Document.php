<?php

namespace App\Models;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

use Auth;

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
        $this->query->leftJoin('categories', 'categories.id', '=', 'documents.category_id');
        $this->query->select('documents.stt', 'documents.id', 'documents.title','documents.updated_at');

        if (strpos(URL::current(), 'admin') < 0) {
            $this->query->where('categories.searchable', 1);
        }

        if (Input::get('cat'))
            $this->query->where('documents.category_id', Input::get('cat'));

        if (Input::get('isBuyed')) {
            $this->query->join('users_documents', 'users_documents.document_id', '=', 'documents.stt');
            $this->query->where('users_documents.user_id', Auth::user()->id);
        }

        if(Input::has('search.value')) {
            $this->_getSearchStringQuery();
        }

        // here order processing
        if (isset($_GET['order']) && !Input::has('search.value')) {
            $this->query->orderBy(
                $this->column_order[$_GET['order']['0']['column']],
                $_GET['order']['0']['dir']
            );
        } else if (isset($this->order) && !Input::has('search.value')) {
            $order = $this->order;
            $this->query->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_getDatatablesQuery();
        $this->query->offset(Input::get('start'));
        $this->query->limit(Input::get('length'));

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
        $this->_getDatatablesQuery();
        $numb = $this->query->count();
        return $numb;
    }

    public function count_all()
    {
        return DB::table($this->table)->count();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
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

             /* create order like
             CASE 
                 WHEN documents.title LIKE '%luật%' AND documents.title LIKE '%đầu%' AND documents.title LIKE '%tư%' THEN 1 
                 WHEN documents.title LIKE '%luật%' AND documents.title LIKE '%đầu%' THEN 2 
                 WHEN documents.title LIKE '%luật%' THEN 3 
                 WHEN documents.title LIKE '%đầu%' THEN 3 
                 WHEN documents.title LIKE '%tư%' THEN 3 
                 ELSE 999 
             END ASC
             */
            $orderCondition = ' CASE ';
            $wordNumb = count($words);
            $i = $wordNumb - 1;
            foreach ($this->column_search as $colSearch) {
                for (; $i >=0 ; $i--) {
                    $orderCondition .= 'WHEN ';
                    $subStr = '';
                    for ($j = 0; $j <= $i ; $j++) {
                        $subStr .= $colSearch.' LIKE \'%'.$words[$j].'%\' ';
                        if ($j != $i) {
                            $subStr .= 'AND ';
                        }
                    }
                    $orderCondition .= $subStr . 'THEN '.($wordNumb - $i).' ';
                }
                $subStr = '';
                for ($i = 1; $i < $wordNumb; $i++) {
                    $subStr .= 'WHEN '.$colSearch.' LIKE \'%'.$words[$i].'%\' THEN '.$wordNumb.' ';
                }
                $orderCondition .= $subStr;
            }
            $orderCondition .= 'ELSE 999 END ASC';
            $this->query->orderByRaw($orderCondition);
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
                }
            }
        }

        return $datas;
    }
}