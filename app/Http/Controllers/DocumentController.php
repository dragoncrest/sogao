<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Document;
use App\Acronym;
use App\Http\Requests;

use DocumentHelper;

class DocumentController extends Controller
{
    //
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

    public function ajaxDieuKhoan($id=0)
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

    public function ajaxThutuc($id)
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
    public function ajaxCheckFileExits($id)
    {
        $doc     = Document::where('id', $id)->first();
        $file    = public_path(). "/documents/".$doc->id.".docx";
        $status  = TRUE;
        $message = "";

        if (!file_exists($file)) {
            $file = public_path(). "/documents/".$doc->id.".doc";
            if (!file_exists($file)) {
                $status  = FALSE;
                $message = "'" . $doc->title . "' không tồn tại.";
            }
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
        $doc  = Document::where('id', $id)->first();
        $file = public_path(). "/documents/".$doc->id.".docx";
        $headers = [
            'Content-Type' => 'application/docx'
        ];
        return response()->download($file, $doc->slug.'.docx', $headers);
    }

    /**
     * Set data to put into view
     * @param $title, stt, content, cates, catID
     * @return array
     */
    private function setData($stt=null, $id=null, $title=null, $content=null, $catID=null)
    {
        return array(
            'stt'     => $stt,
            'id'     => $id,
            'title'   => $title,
            'content' => $content,
            'catID'   => $catID
        );
    }
}
