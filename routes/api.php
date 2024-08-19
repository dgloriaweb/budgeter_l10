<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::group(['middleware' => ['cors', 'json.response']], function () {
//     // public routes
//     Route::post('/register', 'App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
//     Route::post('/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
// });

/*** ROUES FROM LARAVEL8 app */

/* password reset */
//show the form to enter email
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

//process the form and send email
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email|exists:users,email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

/* after user clicked password reset link */
// show the password reset form
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// process the password reset form
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');


/******************* */

Route::get('/email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\ApiAuthController@verifyEmail')->middleware('signed')->name('verification.verify');

/******************* */

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // public routes
    Route::post('/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register', 'App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
    Route::post('/resetPassword', 'App\Http\Controllers\Auth\ResetPasswordController@resetPassword')->name('resetPassword.api');
    Route::get('/getNearbyPlaces', 'App\Http\Controllers\GoogleMapsController@getNearbyPlacesControl')->name('gmaps.api.getnearbyplacescontrol');


    //test routes
    Route::post('/books', 'App\Http\Controllers\Tests\BookController@store');
    Route::post('/books/{id}', 'App\Http\Controllers\Tests\BookController@update');

    // patreon without auth
    Route::get('/patreonInit', 'App\Http\Controllers\PatreonController@getCodeControl');
    Route::get('/patreon', 'App\Http\Controllers\PatreonController@redirect');
    Route::post('/patreonStoreCode', 'App\Http\Controllers\PatreonController@patreonStoreCode');


    // Our protected routes, on the other hand, look like this:
    Route::middleware('auth:api')->group(function () {
        // our routes to be protected will go in here
        Route::post('/logout', 'App\Http\Controllers\Auth\ApiAuthController@logout')->name('logout.api');
        Route::get('/patreonupdate', 'App\Http\Controllers\PatreonController@getPatrons')->name('patreonupdate');
    });
});
Route::middleware('auth:api')->group(function () {
    Route::get('/users', 'App\Http\Controllers\UserController@index')->name('users');
    Route::get('/users/{id}', 'App\Http\Controllers\UserController@show')->name('user');
    // Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/mileages', 'App\Http\Controllers\MileageController@index')->name('mileages');
    Route::get('/last_mileage_data/{id}', 'App\Http\Controllers\MileageController@lastMileageData');
    Route::get('/mileages/{id}', 'App\Http\Controllers\MileageController@show');
    Route::post('/mileages', 'App\Http\Controllers\MileageController@store');
    Route::post('/mileages/{id}', 'App\Http\Controllers\MileageController@update');
    Route::get('/mileage_report/{id}', 'App\Http\Controllers\MileageController@report')->name('mileages_report');
    Route::get('/partners', 'App\Http\Controllers\PartnerController@index')->name('partners');
    Route::post('/partners', 'App\Http\Controllers\PartnerController@store');
    Route::get('/locations', 'App\Http\Controllers\LocationController@index')->name('locations');
    Route::get('/userpartners/{id}', 'App\Http\Controllers\UserPartnerController@show')->name('userpartners');
    Route::get('/getuserpartners/{user_id}', 'App\Http\Controllers\UserPartnerController@getuserpartners')->name('getuserpartners');
    Route::post('/userpartner', 'App\Http\Controllers\UserPartnerController@update')->name('setuserpartner');
    // Route::post('/enableuserpartner/{partner_id}', 'App\Http\Controllers\UserPartnerController@enable')->name('enableuserpartner');

});
