<?php
namespace App\Http\Controllers\User;

use App\Models\Document;
use App\Models\Acronym;
use App\Models\Category;
use App\Models\User;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Input;
use Mail;
use Validator;
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
        $data = $this->setData($doc, 0);
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
        $data = $this->setData(null, $cat);

        return view('search', $data);
    }

    public function register()
    {
        $data = $this->setData(null);
        return view('user.register', $data);
    }

    /**
     * Create view to display message "verify email" after register
     * 
     * @return view
     */
    public function verifyEmail()
    {
        $data = $this->setData(null);
        return view('user.verifyemail', $data);
    }

    /**
     * send feedback from user
     * 
     * @return view
     */
    public function feedback()
    {
        $data = $this->setData(null);
        if (Input::has('_token')) {
            $validator = Validator::make(Input::all(), [
                'content' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect('feedback')
                        ->withErrors($validator)
                        ->withInput();
            }
            $fb = [
                'title'    => Input::get('title'),
                'category' => Input::get('category'),
                'content'  => Input::get('content'),
            ];
            if (!Auth::user()) {
                $userName = 'Khách';
            } else {
                $userName = Auth::user()->name . ' - ' . Auth::user()->id;
            }
            // Mail::send(
            //     'mail.feedback',
            //     $fb,
            //     function($message) use ($userName) {
            //         $message
            //         ->from('sotay56@gmail.com', $userName)
            //         ->to('sotay56@gmail.com', 'Sổ tay 56')
            //         ->subject('[Góp ý]');
            //     }
            // );
            $data['isFeedback'] = true;
        }
        return view('user.feedback', $data);
    }

    /**
     * edit user's information
     *
     */
    public function user()
    {
        if (!Auth::user()) {
            return redirect('/');
        }

        if (Input::has('_token')) {
            $validator = User::validatorEdit(Input::all());
            if ($validator->fails()) {
                return redirect('thongtincanhan')
                        ->withErrors($validator)
                        ->withInput();
            }

            if (Hash::check(Input::get('passwordOld'), Auth::user()->password)) {
                $user               = User::find(Auth::user()->id);
                $user->name         = Input::get('name');
                $user->phone_number = Input::get('phone_number');
                if (Input::get('password')) {
                    $user->password = Hash::make(Input::get('password'));
                }
                $user->save();
                $data['updated'] = UPDATED;
            } else {
                $validator->getMessageBag()->add('passwordOld', 'mật khẩu cũ không chính xác');
                return redirect('thongtincanhan')
                        ->withErrors($validator)
                        ->withInput();
            }
        }
        $data['name']         = Auth::user()->name;
        $data['phone_number'] = Auth::user()->phone_number;
        return view('user.user', $data);
    }

    /**
     * Set data to display in view
     *
     * @param $doc
     * @return array
     */
    private function setData($doc=null, $cat=null)
    {
        $content = !is_null($doc) ? $doc->content : '';
        $cats    = [];

        foreach ($this->cates as $cate) {
            $cats[$cate->id] = $cate->title;
        }

        return [
            'stt'        => !is_null($doc) ? $doc->stt : '',
            'id'         => !is_null($doc) ? $doc->id : '',
            'content'    => $content,
            'isDownload' => !is_null($doc) ? $doc->isDownload : 0,
            'currentCat' => !is_null($cat) ? $cat : '',
            'cats'       => $cats,
        ];
    }
}
