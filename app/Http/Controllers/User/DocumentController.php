<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Acronym;
use App\Models\Category;
use App\Models\UserCoin;
use App\Models\UsersDocument;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

use Auth;
use File;
use DocumentHelper;

class DocumentController extends Controller
{
    private $docPath;

    public function __construct()
    {
        $this->docPath = public_path() . "\upload\documents\\";     // use "/upload/documents/" running on hosting
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
            $cat = $doc->category()->first();
            //check have buy by category or not
            if ($cat->isBuy || $doc->isBuy) {
                $status = $this->_checkUserDocumentStatus($doc);
                if ($status != BUYED) {
                    $data['id'] = $doc->id;
                    $data['stt'] = $doc->stt;
                    $data['title'] = SITE_NAME;
                    $data['currentCat'] = $cat;
                    $data['status'] = $status;
                } else {
                    $data = $this->_setData($doc, $cat);
                }
            } else {
                $data = $this->_setData($doc, $cat);
            }
        } else {
            $data = $this->_setData(null, null);
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
        return view('user.document_list', $this->_setData(null, $cat));
    }

    /**
     * get all document by category's slug
     */
    public function documentBuyeds()
    {
        return view('user.document_list_buyed', $this->_setData(null, null));
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
            echo '<div class="WordSection1">Văn bản đang được cập nhật</div>';
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
    public function ajax_checkFileExits($id = 0)
    {
        $doc     = Document::where('id', $id)->first();
        $catSlug = $doc->category()->first()->slug;
        $status  = TRUE;
        $message = "";

        if (!$this->_checkFileExits($doc->id, $catSlug)) {
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
        $extend   = $this->_checkFileExits($doc->id, $catSlug);
        $catSlug .= '\\';    // use '/' running on hosting
        $file     = $this->docPath . $catSlug . $doc->id . "." . $extend;
        $headers  = ['Content-Type' => 'application/'.$extend];
        return response()->download($file, $doc->slug.".".$extend, $headers);
    }

    /**
     * Process buying document
     * @param string $id document's id
     * @return status
     */
    public function ajaxBuyDocument($id)
    {
        $doc = Document::where('id', $id)->first();
        if (!$doc)
            $doc = Document::where('slug', $id)->first();
        if (!$doc)
            $doc = Document::where('stt', $id)->first();

        $result = $this->_checkUserDocumentStatus($doc);
        $status = FALSE;
        if ($result == BUYED) {
            $status = TRUE;
        } elseif ($result == BUY) {
            DB::beginTransaction();
            $uDoc              = new UsersDocument;
            $uDoc->user_id     = Auth::user()->id;
            $uDoc->document_id = $doc->stt;
            if ($uDoc->save()) {
                $coin = UserCoin::where('user_id', Auth::user()->id)->first();
                $coin = $coin->coin - 1;
                if (UserCoin::where('user_id', Auth::user()->id)->update(['coin' => $coin])) {
                    $status = TRUE;
                    DB::commit();
                }
                if (!$status)
                    DB::rollBack();
            }
        }
        return ['status' => $status];
    }

    /**
     * Set data to display in view
     * @param $doc
     * @return array
     */
    private function _setData($doc, $cat = null)
    {
        $content = !is_null($doc) ? $doc->content : '';
        if (!is_null($doc) && $cat['isHideTitle']) {
            $content = DocumentHelper::ProcessContent($doc, $doc->hasTable);
        }
        return [
            'stt'        => !is_null($doc) ? $doc->stt : '',
            'id'         => !is_null($doc) ? $doc->id : '',
            'title'      => !is_null($doc) ? $doc->title : '',
            'content'    => $content,
            'currentCat' => !is_null($cat) ? $cat : ''
        ];
    }

    /**
     * Check if file is exits on server
     * @param string $id file's id
     * @return string file's extention
     */
    private function _checkFileExits($id, $catSlug)
    {
        $catSlug .= '\\';   // use '/' when running on hosting
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

    /**
    * check if user logged in or have coin or buyed documents
    * @param array $doc document
    * @return status
    */
    private static function _checkUserDocumentStatus($doc)
    {
        $status = FALSE;
        if (!empty($doc)) {
            if (!Auth::user()) {
                $status = LOGIN;
            } else {
                $uCoin = UserCoin::where('user_id', Auth::user()->id)->first();
                $uDoc  = UsersDocument::
                where('user_id', Auth::user()->id)
                ->where('document_id', $doc->stt)
                ->first();
                if (!empty($uDoc)) {
                    $status = BUYED;
                } elseif ($uCoin->coin > 0) {
                    $status = BUY;
                } elseif (!$uCoin->coin) {
                    $status = NOTCOIN;
                }
            }
        }
        return $status;
    }
}
