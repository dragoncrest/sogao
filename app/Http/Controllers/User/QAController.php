<?php
namespace App\Http\Controllers\User;

use App\Models\Category;
use App\Models\QuestionAnswer;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Input;
use DocumentHelper;

class QAController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Question and Answer list
     *
     */
    public function qas()
    {
        $data = $this->setData();
        return view('user.qa_list', $data);
    }

    /**
     * Question and Answer
     *
     * @param $id
     * @return
     */
    public function qa($id=null)
    {
        $data = $this->setData();
        if (Input::has('_token')) {
            $validator = QuestionAnswer::validator(Input::all());
            if ($validator->fails()) {
                return redirect('hoidap')
                        ->withErrors($validator)
                        ->withInput();
            }

            $email = Input::get('email');
            if (Auth::user()) {
                $email = Auth::user()->email;
            }

            $qa = QuestionAnswer::create([
                'email'       => $email,
                'category_id' => Input::get('category') ? Input::get('category') : '',
                'title'       => Input::get('title'),
                'question'    => Input::get('question'),
            ]);
            if ($qa) {
                $data['isQA'] = true;
            }
        } elseif (!is_null($id)) {
            $qa = QuestionAnswer::find($id);
            $qa->answer = DocumentHelper::ProcessContent($qa);

            $data['qa'] = $qa;

            return view('user.qa_detail', $data);
        }
        return view('user.qa', $data);
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
            $row   = [];
            $row[] = $no;
            $row[] = "<a href='". url("/hoidap/".$qa->id) . "'>" . $qa->title . "</a>";
            $row[] = $qa->status ? $answered : $notAnswer;

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
     * Set data to display in view
     *
     * @return array
     */
    private function setData()
    {
        return [
            'cats' => Category::where('parent', 0)->lists('title', 'id')->toArray(),
        ];
    }
}
