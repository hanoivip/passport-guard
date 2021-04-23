<?php

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->get('/login', function () {
    $query = http_build_query([
        'client_id' => config('passport.client_id'),
        'redirect_uri' => route('callback'),
        'response_type' => 'code',
        'scope' => ''
    ]);
    return redirect()->away(config('passport.authorize') . "?" . $query);
})->name('login');
Route::middleware('web')->get('/logout', function () {
    Cookie::queue(Cookie::forget('access_token'));
    Cookie::queue(Cookie::forget('laravel_session'));
    return redirect()->away(config('passport.logout') . '?redirect_uri=' . route('logoutsuccess'));
})->name('logout');
Route::middleware('web')->get('/logoutsuccess', function () {
    return view('hanoivip::landing');
})->name('logoutsuccess');
Route::middleware('web')->get('/register', function () {
    return redirect()->away(config('passport.register'));
})->name('register');
Route::middleware('web')->get('/callback', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;
    Log::debug('.... code ' . $request->code);
    $response = $http->post(config('passport.token'), [
        //'headers' => [
        //    'Content-Type' => 'x-www-form-urlencoded',
        //],
        'form_params' => [
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => route('callback'),
            'code' => $request->code,
        ],
    ]);
    $token = json_decode((string) $response->getBody(), true);
    Log::debug('.... access token ' . $token['access_token']);
    
    Cookie::queue(Cookie::make('access_token',  $token['access_token']));
    return view('hanoivip::landing');
})->name('callback');