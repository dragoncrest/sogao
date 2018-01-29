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

    public function search()
    {
        $cat = '';
        if(Input::has('_token')){
            $cat = Category::find(Input::get("cat"));
        }
        $data = $this->setData("Tìm kiếm", null, $cat);

        return view('search', $data);
    }

    public function register()
    {
        $data = $this->setData("Đăng ký tài khoản", null);
        return view('user.register', $data);
    }

    /**
     * Create view to display message "verify email" after register
     * 
     * @return view
     */
    public function verifyEmail()
    {
        $data = $this->setData("Sổ tay 56", null);
        return view('user.verifyemail', $data);
    }

    /**
     * Set data to display in view
     *
     * @param $doc
     * @return array
     */
    private function setData($title, $doc, $cat=null)
    {
        $content = !is_null($doc) ? DocumentHelper::ProcessContent($doc, $doc->hasTable) : '';
        return [
            'stt'        => !is_null($doc) ? $doc->stt : '',
            'id'         => !is_null($doc) ? $doc->id : '',
            'title'      => !is_null($doc) ? $doc->title : $title,
            'content'    => $content,
            'isDownload' => !is_null($doc) ? $doc->isDownload : 0,
            'currentCat' => !is_null($cat) ? $cat : ''
        ];
    }
}
