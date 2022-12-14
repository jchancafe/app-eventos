<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Google Login
    public function redirectToGoogle()
    {
        //return Socialite::driver('google')->stateless()->redirect();
        return Socialite::driver('google')
            /*->scopes()*/
            ->with(["access_type" => "offline", "prompt" => "consent select_account"])
            ->redirect();
    }

    //Google callback
    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->stateless()->user();

        $this->_registerorLoginUser($user);
        return redirect()->route('home');
    }

    protected function _registerorLoginUser($data)
    {
        $user = User::where('email', $data->email)->first();

        if (!$user) {
            $user = new User();

            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->remember_token  = $data->token;

            $user->save();
        } else {
            //refresando la data del usuario.
            $user->remember_token  = $data->token;
            $user->update();
        }

        Auth::login($user);
    }
}
