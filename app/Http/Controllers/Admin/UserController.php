<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Models\User;
use App\Models\UserCoin;

use Validator;
use DB;

use App\Http\Controllers\Admin\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $content['nav']  = USER;

        $data = ['data' => $content] ;

        return view('admin.user', $data);
    }

    /**
     * create table of user list
     * @return json 
     */
    public function ajaxListUser()
    {
        $user  = new User();
        $list = $user->get_datatables();

        $data = [];
        $no   = $_GET['start'];
        foreach ($list as $u) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $u->name;
            $row[] = $u->email;
            $row[] = $u->coin ? $u->coin : 0;
            $row[] = $u->isActive ? '<span class="user-active">'.ACTIVED.'</span>' : UNACTIVE;
            $row[] = $u->created_at ? date('d-m-Y',strtotime($u->created_at)) : null;
            $row[] = '
                <a href="'.url("admin/user/".$u->id).'" class="button green">
                    <div class="icon"><span class="ico-pencil"></span></div>
                </a>
            ';
            $data[] = $row;
        }

        $output = [
            "draw"            => $_GET['draw'],
            "recordsTotal"    => $user->count_all(),
            "recordsFiltered" => $user->count_filtered(),
            "data"            => $data,
        ];
        echo json_encode($output);
    }

    /**
     * edit user
     * @return array user's data
     */
    public function editUser($id)
    {
        if (!$id) return redirect('admin/user/');

        $myData['nav']  = 'user';
        $myData['cats'] = Category::all();
        $user  = User::find($id);
        $uCoin = $user->Coin()->first();
        $uRole = $user->Role()->first();
        if (Input::has('_token')) {
            $this->validator($id, Input::all());
            $user->name         = Input::get('name');
            $user->email        = Input::get('email');
            $user->password     = Input::get('password') ? Input::get('password') : $user['password'];
            $user->phone_number = Input::get('phone_number');
            if ($uRole['name'] != STR_ADMIN) {
                $user->isActive     = Input::get('isActive');
            }
            DB::beginTransaction();
            if ($user->save()) {
                $uCoin->coin = Input::get('coin') ? Input::get('coin') : 0;
                if ($uCoin->save()) {
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            }
        }
        $myData['user']  = $user;
        $myData['uCoin'] = $uCoin;
        $myData['uRole'] = $uRole;
        return view('admin.userEdit', ['data' => $myData]);
    }

    /**
     * Get a validator for an incoming edit request.
     *
     * @param int $id user's id
     * @param array $data
     */
    protected function validator($id, array $data)
    {
        $validator = Validator::make($data, [
            'name'         => 'required|max:255',
            'email'        => 'required|email',
            'coin'         => 'numeric|min:0',
            'phone_number' => 'max:15|regex:/(^[0-9 ]+$)+/'
        ]);
        if ($validator->fails()) {
            return redirect('admin/user/'.$id)
                    ->withErrors($validator)
                    ->withInput();
        }
    }
}
