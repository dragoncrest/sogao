<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Document;
use App\Acronym;
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

    public function document($id)
    {
        $doc = Document::where('id', $id)->first();

        if(!$doc)
            $doc = Document::where('slug', $id)->first();
        if(!$doc)
            $doc = Document::where('stt', $id)->first();

        if($doc){
            $data = $this->setData(
                $doc->stt,
                $doc->id,
                $doc->title,
                DocumentHelper::ProcessContent($doc->content, $doc->hasTable),
                0
           );
        }else 
            $data = $this->setData("Không tìm thấy",null,null,null);

        return view('document', $data);
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
     * Set data to put into view
     * @param $title, stt, content, cates, catID
     * @return array
     */
    private function setData($stt=null, $id=null, $title=null, $content=null, $catID=null)
    {
        return [
            'stt'     => $stt,
            'id'      => $id,
            'title'   => $title,
            'content' => $content,
            'catID'   => $catID
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
