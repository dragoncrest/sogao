<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Category;

use App\Http\Requests;

use Input;
use Validator;

class DocumentController extends Controller
{
    private $myData;

    public function index($idCat=1)
    {
        $this->SetCategory($idCat);
        return view('admin.document', ['data' => $this->myData]);
    }

    /**
     * create table of list document by category
     * @return json 
     */
    public function ajax()
    {
        $doc  = new Document();
        $list = $doc->get_datatables();
        
        $data = array();
        $no   = $_GET['start'];
        foreach ($list as $document) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $document->id;
            $row[] = $document->title;
            $row[] = $document->updated_at ? date('d-m-Y',strtotime($document->updated_at)) : null;
            $row[] = '
                <a href="'.url("admin/document/edit/".$document->stt).'" class="button green">
                    <div class="icon"><span class="ico-pencil"></span></div>
                </a>
                <a id="'.$document->stt.'" onclick=$.confirmdelete("'.$document->id.'",'.$document->stt.') href="javascript:void(0);" class="button red delete-">
                    <div class="icon"><span class="ico-remove"></span></div>
                </a>
            ';
            $data[] = $row;
        }
 
        $output = [
            "draw"            => $_GET['draw'],
            "recordsTotal"    => $doc->count_all(),
            "recordsFiltered" => $doc->count_filtered(),
            "data"            => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

    public function delete($stt=null)
    {
        $status = false;
        if (!$stt) return $status;

        if (Document::find($stt)->delete()) {
            $status = true;
        }
        return [
            'status' => $status
        ];
    }

    public function edit($stt=null)
    {
        $doc = '';
        if($stt) $doc = Document::where('stt', $stt)->first(); 
            
        $this->SetDocument($doc);
        $this->SetCategory();  

        if (Input::has('_token')) {
            $data = Input::except(array('token'));  
             
            $rule = [
                'title'  =>'required',
                'content'=>'required'
            ];

            $message = [ 
                'title.required'   => 'Chưa điền tên',
                'content.required' => 'Chưa điền nội dung'
            ];
            $valid = Validator::make($data, $rule, $message);
        }else
            $valid = Validator::make(array(), array(), array());

            $this->myData['errors'] = $valid->errors();

       //when submit update or create new
        if (!$valid->fails() && Input::has('_token'))
        {
            $doc = new Document;
            $stt = Input::get('stt');

            if($stt){
                $doc = Document::find($stt);
            }

            $doc->id          = preg_replace('/\W/', '', Input::get('id'));
            $doc->title       = Input::get('title');
            $doc->slug        = str_slug($doc->title);
            $doc->content     = $this->Extract(Input::get('content'));
            $doc->category_id = Input::get('cat');
            $doc->hasTable    = Input::get('hasTable');
            $doc->isDownload  = Input::get('isDownload');
            $doc->isBuy       = Input::get('isBuy');
            $doc->updated_at  = time();
            $doc->save();
            
            $this->SetDocument($doc);
        }

        $option = array();
        foreach($this->myData['cats'] as $cat){
            $option[$cat->id] = $cat->title;
        }
        $this->myData['options'] = $option;

        return view('admin/documentEdit', ['data' => $this->myData]);
    }

    private function SetDocument($doc=null)
    {
        $arr                = array();
        $arr['stt']         = ($doc) ? $doc->stt : null;
        $arr['id']          = ($doc) ? $doc->id : '';
        $arr['title']       = ($doc) ? $doc->title : '';
        $arr['content']     = ($doc) ? $doc->content : '';
        $arr['category_id'] = ($doc) ? $doc->category_id : 1;
        $arr['hasTable']    = ($doc) ? $doc->hasTable : 0;
        $arr['isDownload']  = ($doc) ? $doc->isDownload : 0;
        $arr['isBuy']       = ($doc) ? $doc->isBuy : 0;

        $this->myData['doc'] = $arr;
    }

    /**
     * set category to use in navigation and header table
     */
    private function SetCategory($idCat=1)
    {
        $this->myData['nav']      = 'doc';
        $this->myData['cats']     = Category::all();
        $this->myData['catID']    = $idCat;
        $this->myData['catTitle'] = '';
        
        foreach($this->myData['cats'] as $cat) {
            if($cat->id == $idCat){
                $this->myData['catTitle'] = $cat->title;
                break;
            }
        }
    }

    /**
     * Extract <div class=WordSection1> content in page
     * 
     * @param string $string raw
     * @return string
     */
    private function Extract($string)
    {
        //$string = preg_replace("/text-indent(.*?)\\;/si", "", $string);

        preg_match("/<div[^>]*class=WordSection1>(.*?)<\\/div>/si", $string, $match);
        if(!isset($match[0])){
            if(!strpos($string, 'WordSection1'))
                return '<div class=WordSection1>'.$string.'</div>';
            else 
                return $string; 
        }
        return html_entity_decode($match[0]);
    }
}
