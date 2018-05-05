<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserCoin;

use Validator;
use Mail;
use Hash;

use App\Http\Controllers\User\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/verifyemail';
    protected $registerView = 'auth.register';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
    * verify user when user click verify link from email
    *
    * @param string $confirmation_code
    */
    public function verifyUser($confirmation_code)
    {
        if (!$confirmation_code) {
            throw new InvalidConfirmationCodeException;
        }
        $user = User::whereVerificationCode(htmlentities($confirmation_code, ENT_QUOTES))->first();
        if (!$user) {
            throw new InvalidConfirmationCodeException;
        }
        $user->isActive = 1;
        $user->save();
        Auth::guard($this->getGuard())->login($user);
        return redirect('/');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => 'required|max:255',
            'email'        => 'required|email|max:255|unique:users',
            'password'     => 'required|min:6|confirmed',
            'phone_number' => 'numeric'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();

        $status = true;
        $code   = str_random(5) . time();
        $user   = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'phone_number'      => $data['phone_number'],
            'verification_code' => $code
        ]);

        if ($user) {
            $coin = UserCoin::create([
                'user_id' => $user->id,
                'coin'    => 0
            ]);
            if (!$coin) {
                $status = false;
                DB::rollBack();
            } else {
                // Mail::send(
                //     'mail.verify',
                //     ['verification_code' => $code],
                //     function($message) use ($data) {
                //         $message
                //         ->from('sotay56@gmail.com', 'Sổ tay 56')
                //         ->to($data['email'], $data['name'])
                //         ->subject('Xác nhận tài khoản');
                //     }
                // );
            }
        } else {
            $status = false;
            DB::rollBack();
        }

        if ($status) DB::commit();

        return $user;
    }

    /**
     * Check if user is actived or not
     *
     * @param  array $request
     * @return status
     */
    protected function checkActiveUser($request)
    {
        $user = User::where('email', $request->get($this->loginUsername()))->first();
        if (empty($user)) return false;

        $isMatch = Hash::check($request->get('password'), $user->password, []);
        if (!$isMatch) return false;

        if ($isMatch && !$user->isActive) {
            return UNACTIVE;
        } else {
            return ACTIVED;
        }
    }
}
