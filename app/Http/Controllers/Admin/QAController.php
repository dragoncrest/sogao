<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\QuestionAnswer;

use DB;
use Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class QAController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $content['nav']  = QA;

        $data = ['data' => $content] ;

        return view('admin.qa', $data);
    }

    /**
     * create table of question and answer list
     * @return json 
     */
    public function ajaxListQA()
    {
        $qaModel = new QuestionAnswer();
        $list    = $qaModel->get_datatables();
        $data    = [];
        $no      = $_GET['start'];

        $answered  = '<span class="">Đã trả lời</span>';
        $notAnswer = '<span class="cred">Chưa trả lời</span>';

        foreach ($list as $qa) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $qa->title;
            $row[] = $qa->email;
            $row[] = $qa->status ? $answered : $notAnswer;
            $row[] = $qa->display ? 'Có' : 'Không';
            $row[] = $qa->created_at ? date('d-m-Y',strtotime($qa->created_at)) : null;
            $row[] = '
                <a href="'.url("admin/qa/edit/".$qa->id).'" class="button green">
                    <div class="icon"><span class="ico-pencil"></span></div>
                </a>
                <a id="'.$qa->id.'" onclick=$.deleteQA('.$qa->id.') href="javascript:void(0);" class="button red delete-">
                    <div class="icon"><span class="ico-remove"></span></div>
                </a>
            ';
            $data[] = $row;
        }

        $output = [
            "draw"            => $_GET['draw'],
            "recordsTotal"    => $qaModel->count_all(),
            "recordsFiltered" => $qaModel->count_filtered(),
            "data"            => $data,
        ];
        echo json_encode($output);
    }

    /**
     * edit qa
     * @return array qa's data
     */
    public function edit($id=null)
    {
        $cates          = Category::all();
        $myData['nav']  = QA;
        $myData['cats'] = [];

        if (empty($id)) {
            $qa = new QuestionAnswer();
        } else {
            $qa = QuestionAnswer::find($id);
        }

        if (Input::has('_token')) {
            $validator = QuestionAnswer::validator(Input::all());
            if ($validator->fails()) {
                return redirect('admin/qa/edit/'.$id)
                        ->withErrors($validator)
                        ->withInput();
            }

            if (Input::get('category')) {
                $qa->category_id = Input::get('category');
            }
            if (Input::get('answer')) {
                $qa->status = TRUE;
            } else {
                $qa->status = FALSE;
            }

            $qa->title    = Input::get('title');
            $qa->question = Input::get('question');
            $qa->answer   = Input::get('answer');
            $qa->display  = Input::get('display');
            $qa->hasTable = Input::get('hasTable');
            $qa->hasLV    = Input::get('hasLV');

            if ($qa->save()) {
                $myData['updated'] = UPDATED;
                if ($qa->status && $qa->email) {
                    $this->sendMail($qa);
                }
                if (empty($id)) {
                    return redirect('admin/qa/');
                }
            }
        }

        foreach ($cates as $cate) {
            $myData['cats'][$cate->id] = $cate->title;
        }
        return view('admin.qaEdit', ['data' => $myData, 'qa' => $qa]);
    }

    /**
     * delete qa
     * @return boll $status
     */
    public function ajaxDelete($id=null)
    {
        $status = false;
        if (empty($id)) return $status;

        if (QuestionAnswer::find($id)->delete()) {
            $status = true;
        }
        return [
            'status' => $status
        ];
    }

    /**
    * send mail to user have question
    * @param array $qa
    */
    private function sendMail($qa)
    {
        if ($qa->category_id) {
            $category = $qa->Category()->first();
        }
        $data = [
            'id'       => $qa->id,
            'title'    => $qa->title,
            'question' => $qa->question,
            'answer'   => $qa->answer,
            'category' => $qa->category_id ? $category->title : '',
        ];
        // Mail::send(
        //     'mail.answer',
        //     $data,
        //     function($message) use ($qa) {
        //         $message
        //         ->from('sotay56@gmail.com', 'Sổ tay 56')
        //         ->to($qa->email, '')
        //         ->subject('[Hỏi đáp] '.$qa->title);
        //     }
        // );
    }
}
