<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Category;

use Input;
use Validator;

class CategoryController extends Controller
{

    private $myData;

    public function index()
    {
        $content['nav']  = 'cat';
        $data = ['data' => $content];

        return view('admin.category', $data);
    }

    /**
     * create table of list category
     * @return json 
     */
    public function ajax()
    {
        $cats = new Category();
        $list = $cats->get_datatables();

        $data = [];
        $no   = $_GET['start'];
        foreach ($list as $cat) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $cat->id;
            $row[] = $cat->title;
            $row[] = $cat->parent;
            $row[] = $cat->isDownload ? 'Có' : 'Không';
            $row[] = $cat->isBuy ? 'Có' : 'Không';
            $row[] = '  <a href="'.url("admin/category/edit/".$cat->id).'" class="button green">
                            <div class="icon"><span class="ico-pencil"></span></div>
                        </a>
                        <a onclick=$.deleteCat("'.str_replace(" ", '-', $cat->title).'",'.$cat->id.') href="#" class="button red">
                            <div class="icon"><span class="ico-remove"></span></div>
                        </a>';

            $data[] = $row;
        }

        $output = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $cats->count_all(),
            "recordsFiltered" => $cats->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

    public function edit($id=null)
    {
        $cat = '';
        if($id) $cat = Category::find($id);

        $myData['nav']  = 'cat';
        $myData['cats'] = Category::all();    //list category on sidebar 
        $myData['cat']  = $this->setCategory($cat);

        if (Input::has('_token')) {
            $data = Input::except(array('token'));

            $rule = ['title'  =>'required'];

            $message = ['title.required' => 'Chưa điền tên'];
            $valid = Validator::make($data, $rule, $message);

            if (!$valid->fails()) {
                $cat = new Category;
                $id  = Input::get('id');

                if($id) $cat = Category::find($id); 

                $cat->title       = Input::get('title');
                $cat->slug        = str_slug(Input::get('title'));
                $cat->parent      = Input::get('parent');
                $cat->searchable  = Input::get('searchable');
                $cat->isDownload  = Input::get('isDownload');
                $cat->isHideTitle = Input::get('isHideTitle');
                $cat->isBuy       = Input::get('isBuy');
                $cat->save(); 

                $myData['cat']  = $this->setCategory($cat);
            }
        } else
            $valid = Validator::make([], [], []);

        $myData['errors'] = $valid->errors();

        $i      = 1;
        $option = [];
        $option[0] = '-----------';
        foreach ($myData['cats'] as $cat) {
            $option[$i] = $cat->title;
            $i++;
        }
        $myData['options'] = $option;

        return view('admin/categoryEdit', ['data' => $myData]);
    }

    public function delete($id=null)
    {
        if(!$id) return;

        $cat   = Category::find($id);
        $title = $cat->title;

        $cat->delete();

        return redirect('admin/category/')->with('delete', $title);
    }

    private function SetDocument($doc=null)
    {
        $arr            = [];
        $arr['stt']     = ($doc) ? $doc->stt : null;
        $arr['id']      = ($doc) ? $doc->id : '';
        $arr['title']   = ($doc) ? $doc->title : '';
        $arr['content'] = ($doc) ? $doc->content : '';
        $arr['category']= ($doc) ? $doc->category : 1;
        
        $this->myData['doc'] = $arr;
    }

    private function setCategory($cat)
    {
        $data['id']          = $cat ? $cat->id : '';
        $data['title']       = $cat ? $cat->title : '';
        $data['parent']      = $cat ? $cat->parent : '';
        $data['searchable']  = $cat ? $cat->searchable : '';
        $data['isDownload']  = $cat ? $cat->isDownload : '';
        $data['isHideTitle'] = $cat ? $cat->isHideTitle : '';
        $data['isBuy']       = $cat ? $cat->isBuy : '';

        return $data;
    }
}
