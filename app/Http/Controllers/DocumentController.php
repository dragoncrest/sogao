<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Document;
use App\Acronym;
use App\Category;
use App\Http\Requests;

use File;
use DocumentHelper;

class DocumentController extends Controller
{
    private $docPath;

    public function __construct()
    {
        $this->docPath = public_path() . "\upload\documents\\";
    }

    /**
     * get document by id
     */
    public function document($id)
    {
        $doc = Document::where('id', $id)->first();

        if(!$doc)
            $doc = Document::where('slug', $id)->first();
        if(!$doc)
            $doc = Document::where('stt', $id)->first();

        if ($doc) {
            $cat  = $doc->category()->first();
            $data = $this->setData($doc, $cat);
        } else {
            $data = $this->setData(null, null);
        }

        return view('document', $data);
    }

    /**
     * get all document by category's slug
     */
    public function documents($catSlug = null)
    {
        if (is_null($catSlug)) return;

        $cat = Category::where('slug', $catSlug)->first();
        return view('user.document_list', $this->setData(null, $cat));
    }

    /**
     * create table of list document by category's id
     * @return json 
     */
    public function ajaxTable()
    {
        $doc  = new Document();
        $list = $doc->get_datatables();

        $data = [];
        $no = $_GET['start'];
        foreach ($list as $document) {
            $no++;
            $id    = $document->id ? $document->id : $document->stt;
            $row   = array();
            $row[] = $no;
            $row[] = "<a href='".url("/document/".$id)."'>".$document->title."</a>";
            $row[] = $document->updated_at ? date('d-m-Y',strtotime($document->updated_at)) : null;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw"            => $_GET['draw'],
            "recordsTotal"    => $doc->count_all(),
            "recordsFiltered" => $doc->count_filtered(),
            "data"            => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajaxDieuKhoan($id = 0)
    {
        if(!$id) return;

        $id  = rawurldecode($id);
        $arr = Document::where('id', $id)->first();

        if($arr){
            //echo $arr->content;
        }
        else
            echo '<div class="WordSection1">Dữ liệu ' . $id . " đang được cập nhật</div>";
    }

    public function ajaxThutuc($id = 0)
    {
        if(!$id) return;

        $id  = rawurldecode($id);
        $arr = Document::where('id', $id)->first();

        if($arr){
            $content = $this->ProcessContent($arr->content);
            return view('document', ['content' => $content]);
        }
        else
            echo '<div class="WordSection1">Dữ liệu ' . $id . " đang được cập nhật</div>";
    }

    /**
     * Ajax check if file is exits on server
     * @param string $id file's id
     * @return array
     */
    public function ajaxCheckFileExits($id = 0)
    {
        $doc     = Document::where('id', $id)->first();
        $catSlug = $doc->category()->first()->slug;
        $status  = TRUE;
        $message = "";

        if (!$this->checkFileExits($doc->id, $catSlug)) {
            $status  = FALSE;
            $message = "'" . $doc->title . "' không tồn tại.";
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    /**
     * Download file
     * @param string $id file's id
     * @return file
     */
    public function download($id)
    {
        $doc      = Document::where('id', $id)->first();
        $catSlug  = $doc->category()->first()->slug;
        $extend   = $this->checkFileExits($doc->id, $catSlug);
        $catSlug .= '\\';
        $file     = $this->docPath . $catSlug . $doc->id . "." . $extend;
        $headers  = ['Content-Type' => 'application/'.$extend];
        return response()->download($file, $doc->slug.".".$extend, $headers);
    }

    /**
     * Set data to display in view
     * @param $doc
     * @return array
     */
    private function setData($doc, $cat = null)
    {
        $content = !is_null($doc) ? DocumentHelper::ProcessContent($doc->content, $doc->hasTable) : '';
        return [
            'stt'        => !is_null($doc) ? $doc->stt : '',
            'id'         => !is_null($doc) ? $doc->id : '',
            'title'      => !is_null($doc) ? $doc->title : '',
            'content'    => $content,
            'isDownload' => !is_null($doc) ? $doc->isDownload : 0,
            'currentCat' => !is_null($cat) ? $cat : ''
        ];
    }

    /**
     * Check if file is exits on server
     * @param string $id file's id
     * @return string file's extention
     */
    private function checkFileExits($id, $catSlug)
    {
        $catSlug .= '\\';
        $file     = $this->docPath.$catSlug.$id.".docx";
        if (!File::exists($file)) {
            $file = $this->docPath.$catSlug.$id.".doc";
            if (!File::exists($file)) {
                return FALSE;
            } else {
                return "doc";
            }
        } else {
            return "docx";
        }
    }
}
