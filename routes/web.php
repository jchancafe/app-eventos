<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

/*
Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->stateless()->user();
    //dd($user);
    try {

        $userExists = User::where('external_id', $user->id)->where('external_auth', 'google')->first();
        //dd($userExists);
        if ($userExists) {
            //refresando la data del usuario.
            $userExists['refresh_token']  = $user->token;

            $userExists->update();

            Auth::login($userExists);
            return redirect('/dashboard');
        } else {
            $userNew = User::create([
                'avatar' => $user->avatar,
                'name' => $user->name,
                'email' => $user->email,
                'refresh_token' => $user->tocken,
                'external_id' => $user->id,
                'external_auth' => 'google',
            ]);

            Auth::login($userNew);
            return redirect('/dashboard');
        }
    } catch (Exception $e) {
        dd($e->getMessage());
    }
});*/
