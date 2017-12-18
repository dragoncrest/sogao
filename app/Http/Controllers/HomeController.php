<?php
namespace App\Http\Controllers;

use App\Document;
use App\Acronym;
use App\Category;

use App\Http\Requests;
use Illuminate\Http\Request;

use Input;
use DocumentHelper;

class HomeController extends Controller
{
    private $cates;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cates = Category::where('parent', 0)->get();
    }

    public function index()
    {
        $doc = Document::where('slug', 'trang-chu')->first();
        $data = $this->setData("Sổ tay 56", $doc, 0);
        return view('home', $data);
    }

    public function ajax($id)
    {
        $document = Document::where('id', $id)->first();
        $document = $document->content;
        $document = DocumentHelper::ProcessContent($document);

        echo $document;
    }

    public function document($id)
    {
        $doc = Document::where('id', $id)->first();

        if(!$doc)
            $doc = Document::where('slug', $id)->first();
        if(!$doc)
            $doc = Document::where('stt', $id)->first();

        if($doc){
            $data = $this->setData('', $doc, 0);
        }else 
            $data = $this->setData("Không tìm thấy", null, 0);

        return view('document', $data);
    }

    public function search()
    {
        $catID = '';
        if(Input::has('_token')){
            $catID = Input::get("cat");
        }
        $data = $this->setData("Tìm kiếm", null, $catID);

        return view('search', $data);
    }

    /**
     * create table of list document by category
     * @return json 
     */
    public function ajaxTable()
    {
        $doc  = new Document();
        $list = $doc->get_datatables();

        $data = array();
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

    public function register()
    {
        $data = $this->setData("Sổ tay 56", null, 0);
        return view('user.register', $data);
    }

    /**
     * Set data to display in view
     * @param $doc
     * @return array
     */
    private function setData($title, $doc, $catID=null)
    {
        $content = !is_null($doc) ? DocumentHelper::ProcessContent($doc->content, $doc->hasTable) : '';
        return [
            'stt'        => !is_null($doc) ? $doc->stt : '',
            'id'         => !is_null($doc) ? $doc->id : '',
            'title'      => !is_null($doc) ? $doc->title : $title,
            'content'    => $content,
            'isDownload' => !is_null($doc) ? $doc->isDownload : 0,
            'catID'      => $catID
        ];
    }
}
