<?php

namespace App\Models;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'verification_code', 'isActive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $query;
    protected $table = 'users';
    protected $column_order = [null, 'users.name','users.email', 'user_coins.coin', 'users.isActive']; //set column field database for datatable orderable
    protected $column_search = ['name', 'email']; //set column field database for datatable searchable 
    protected $order = ['users.created_at' => 'desc']; // default order 

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    protected function checkIfUserHasRole($role)
    {
        $role = Role::where('name', $role)->first();
        
        if(strtolower($role['id']) == strtolower($this->role_id))
            return TRUE;
        else 
            return null;
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if($this->checkIfUserHasRole($role))
                    return true;
            }
        } else
            return $this->checkIfUserHasRole($roles);

        return FALSE;
    }

    private function _get_datatables_query()
    {

        $this->query = DB::table($this->table);
        $this->query->leftJoin('user_coins', 'user_coins.user_id', '=', 'users.id');
        $this->query->select('users.id', 'users.name', 'users.email', 'users.created_at', 'user_coins.coin', 'users.isActive');

        $i = 0;
        foreach ($this->column_search as $item) {
            if($_GET['search']['value']) {

                if ($i===0) {
                    $this->query->where($item, 'like', '%'.$_GET['search']['value'].'%');
                } else {
                    $this->query->orWhere($item, 'like', '%'.$_GET['search']['value'].'%');
                }
            }
            $i++;
        }

        if(isset($_GET['order'])) {
            $this->query->orderBy($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->query->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_GET['length'] != -1) {
            $this->query->offset($_GET['start']);
            $this->query->limit($_GET['length']);
        }

        return $this->query->get();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();

        return $this->query->count();
    }

    public function count_all()
    {
        return DB::table($this->table)->count();
    }

    public function coin()
    {
        return $this->hasOne('App\Models\UserCoin');
    }
}
