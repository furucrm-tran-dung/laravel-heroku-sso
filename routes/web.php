<?php

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

Route::get('/login/{provider}', 'Auth\SocialAccountController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration');
Route::post('/2fa', function () {
    return redirect('home');
})->name('2fa')->middleware('2fa');
Route::get('/re-authenticate', 'HomeController@reauthenticate');

Route::get('/photos', 'PhotosController@showForm');
Route::post('/photos', 'PhotosController@submitForm');
Route::get('/photos/upload', 'PhotosController@showFormUpload');
Route::post('/photos/upload', 'PhotosController@submitFormUpload');
