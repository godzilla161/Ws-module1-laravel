<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth','Auth\LoginController@login');
Route::post('/register','Auth\RegisterController@register');

Route::post('/posts','PostController@store');
Route::post('/posts/{post_id}','PostController@edit');
Route::delete('/posts/{post_id}','PostController@delete');
Route::get('/posts','PostController@get');
Route::get('/posts/{post_id}','PostController@getOne');
Route::get('/posts/tag/{tag}','PostController@search');

Route::post('/posts/{post_id}/comments','CommentController@store');
Route::dlete('/posts/{post_id}/comments/{comment_id}','CommentController@store');