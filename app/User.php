<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role');
    }
    
    protected function checkIfUserHasRole($role)
    {
        $role = Role::where('name', $role)->first();
        
        if(strtolower($role['id']) == strtolower($this->role))
            return TRUE;
        else 
            return null;
    }
    
    public function hasRole($roles)
    {
        if(is_array($roles)){
            foreach ($roles as $role) {
                if($this->checkIfUserHasRole($role))
                    return true;
            }
        }else
            return $this->checkIfUserHasRole($roles);
        
        return FALSE;
    }
}
