<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});


Route::post('forgot_password', [ProfileController::class, 'forgotPassword']);
Route::post('reset_password', [ProfileController::class, 'resetPassword']);
Route::post('send_code', [ProfileController::class, 'sendCode']);
Route::post('verify_code', [ProfileController::class, 'verifyCode']);
Route::get('countries', [HomeController::class, 'countries']);
Route::get('cities', [HomeController::class, 'cities']);
Route::get('categories', [HomeController::class, 'categories']);
Route::get('features', [HomeController::class, 'features']);
Route::get('services', [HomeController::class, 'services']);
Route::get('sliders', [HomeController::class, 'sliders']);
Route::get('home', [HomeController::class, 'home']);
Route::get('property', [HomeController::class, 'property']);
Route::get('reviews', [HomeController::class, 'reviews']);
Route::get('properties', [HomeController::class, 'properties']);
Route::get('user_details', [HomeController::class, 'userDetails']);
Route::get('app_details', [HomeController::class, 'appDetails']);
Route::post('send_message', [HomeController::class, 'sendMessage']);

Route::get('test', function (){
    return 'test';
})->middleware('auth:sanctum');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('update_profile', [ProfileController::class, 'update']);
    Route::post('update_password', [ProfileController::class, 'updatePassword']);


    Route::post('add_property', [HomeController::class, 'addProperty']);
    Route::post('edit_property', [HomeController::class, 'editProperty']);
    Route::post('delete_property', [HomeController::class, 'deleteProperty']);
    Route::post('toggle_favorite', [HomeController::class, 'toggleFavorite']);
    Route::get('favorites', [HomeController::class, 'favorites']);
    Route::post('add_review', [HomeController::class, 'addReview']);
    Route::get('my_properties', [HomeController::class, 'myProperties']);
    Route::get('notifications', [HomeController::class, 'notifications']);
    Route::post('report_review', [HomeController::class, 'reportReview']);
});

