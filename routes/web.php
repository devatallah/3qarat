<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewReportController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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

Route::get('360_image/{property}', function (\App\Models\Property $property) {

    $link = $property->getAttribute('360_image');
    return view('360_image', compact('link'));
});


Auth::routes();
Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('admin/login', [LoginController::class, 'login'])->name('admin_login');
    Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin_logout');
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth:admin']], function () {


    Route::get('', function (\Illuminate\Http\Request $request) {
        return redirect('admin/home');
    });
    Route::get('/home', [HomeController::class, 'home']);
    Route::get('/get_users', [HomeController::class, 'getUsers']);
    Route::get('/get_properties', [HomeController::class, 'getProperties']);

    Route::get('/profile', function () {
        return view('admin.profile');
    });
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'changePassword']);

    Route::group(['prefix' => 'ads', 'middleware' => ['permission:ads']], function () {
        Route::get('/', [AdController::class, 'index']);
        Route::post('/', [AdController::class, 'store']);
        Route::put('/{ad}', [AdController::class, 'update']);
        Route::delete('/{ad}', [AdController::class, 'destroy']);
        Route::get('/indexTable', [AdController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'categories', 'middleware' => ['permission:categories']], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
        Route::get('/indexTable', [CategoryController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'cities', 'middleware' => ['permission:cities']], function () {
        Route::get('/', [CityController::class, 'index']);
        Route::post('/', [CityController::class, 'store']);
        Route::put('/{city}', [CityController::class, 'update']);
        Route::delete('/{city}', [CityController::class, 'destroy']);
        Route::get('/indexTable', [CityController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'contacts', 'middleware' => ['permission:contacts']], function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::post('/', [ContactController::class, 'store']);
        Route::put('/{contact}', [ContactController::class, 'update']);
        Route::delete('/{contact}', [ContactController::class, 'destroy']);
        Route::get('/indexTable', [ContactController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'countries', 'middleware' => ['permission:countries']], function () {
        Route::get('/', [CountryController::class, 'index']);
        Route::post('/', [CountryController::class, 'store']);
        Route::put('/{country}', [CountryController::class, 'update']);
        Route::delete('/{country}', [CountryController::class, 'destroy']);
        Route::get('/indexTable', [CountryController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'features', 'middleware' => ['permission:features']], function () {
        Route::get('/', [FeatureController::class, 'index']);
        Route::post('/', [FeatureController::class, 'store']);
        Route::put('/{feature}', [FeatureController::class, 'update']);
        Route::delete('/{feature}', [FeatureController::class, 'destroy']);
        Route::get('/indexTable', [FeatureController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'review_reports', 'middleware' => ['permission:review_reports']], function () {
        Route::get('/', [ReviewReportController::class, 'index']);
        Route::post('/', [ReviewReportController::class, 'store']);
        Route::put('/{review}', [ReviewReportController::class, 'update']);
        Route::delete('/{review}', [ReviewReportController::class, 'destroy']);
        Route::get('/indexTable', [ReviewReportController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'reviews', 'middleware' => ['permission:reviews']], function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
        Route::get('/indexTable', [ReviewController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'notifications', 'middleware' => ['permission:notifications']], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store']);
        Route::put('/{notification}', [NotificationController::class, 'update']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::get('/indexTable', [NotificationController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'pages', 'middleware' => ['permission:pages']], function () {
        Route::get('/', [PageController::class, 'index']);
        Route::post('/', [PageController::class, 'store']);
        Route::put('/{page}', [PageController::class, 'update']);
        Route::delete('/{page}', [PageController::class, 'destroy']);
        Route::get('/indexTable', [PageController::class, 'indexTable']);
        Route::get('/get_content/{page}', [PageController::class, 'getContent']);
    });
    Route::group(['prefix' => 'properties', 'middleware' => ['permission:properties']], function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('/{property}', [PropertyController::class, 'update']);
        Route::delete('/{property}', [PropertyController::class, 'destroy']);
        Route::get('/indexTable', [PropertyController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'property_images', 'middleware' => ['permission:property_images']], function () {
        Route::get('/', [PropertyImageController::class, 'index']);
        Route::post('/', [PropertyImageController::class, 'store']);
        Route::put('/{property_image}', [PropertyImageController::class, 'update']);
        Route::delete('/{property_image}', [PropertyImageController::class, 'destroy']);
        Route::get('/indexTable', [PropertyImageController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'services', 'middleware' => ['permission:services']], function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::put('/{service}', [ServiceController::class, 'update']);
        Route::delete('/{service}', [ServiceController::class, 'destroy']);
        Route::get('/indexTable', [ServiceController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'sliders', 'middleware' => ['permission:sliders']], function () {
        Route::get('/', [SliderController::class, 'index']);
        Route::post('/', [SliderController::class, 'store']);
        Route::put('/{slider}', [SliderController::class, 'update']);
        Route::delete('/{slider}', [SliderController::class, 'destroy']);
        Route::get('/indexTable', [SliderController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'settings', 'middleware' => ['permission:settings']], function () {
        Route::get('/', [SettingController::class, 'edit'])->name('setting');
        Route::put('/', [SettingController::class, 'update'])->name('settings.update');
    });
    Route::group(['prefix' => 'users', 'middleware' => ['permission:users']], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        Route::get('/indexTable', [UserController::class, 'indexTable']);
    });
    Route::group(['prefix' => 'admins', 'middleware' => ['permission:admins']], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/', [AdminController::class, 'store']);
        Route::put('/{admin}', [AdminController::class, 'update']);
        Route::delete('/{admin}', [AdminController::class, 'destroy']);
        Route::get('/indexTable', [AdminController::class, 'indexTable']);
    });
});
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
