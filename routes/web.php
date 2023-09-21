<?php

use Illuminate\Support\Facades\Route;

//////////////////////////////////////
use App\Http\Controllers\UserController;
use App\Http\Controllers\newsshower;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Http\Controllers\UserMapController;
use App\Http\Controllers\EditAccController;

///////////////////////////////////
use App\Models\Pothole;
use App\Models\Roadblock;
use App\Models\Help;
use App\Http\Controllers\PotholeController;
use App\Http\Controllers\RoadblockController;
use App\Http\Controllers\HelpController;
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



Auth::routes();
Route::resource('users', UserController::class)->middleware('is_admin');
Route::resource('user', EditAccController::class);
Route::get('/edit' , [App\Http\Controllers\EditAccController::class , 'edit']) -> name('edit');
//Route::post('/update', [App\Http\Controllers\EditAccController::class , 'update']) -> name('update');
Route::get('/profile/{user}', [App\Http\Controllers\HomeController::class, 'index'])->name('profile');


//Admin

Route::resource('fires', newsshower::class)->middleware('is_admin');

Route::get('/userinfo',[App\Http\Controllers\UserMapController::class , 'getUserInfo']);
Route::get('usernumber/{id}',[App\Http\Controllers\UserMapController::class , 'getUserNumber']);

//Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'adminhome']) -> name('admin.home') -> middleware('is_admin');

Route::post('news',  [App\Http\Controllers\newsshower::class, 'store'])-> middleware('is_admin');
Route::get('/',  [App\Http\Controllers\newsshower::class, 'show'])->name('home');
Route::get('delete/{id}',  [App\Http\Controllers\newsshower::class, 'destroy'])-> middleware('is_admin');

//Route::view('news','moder') -> middleware('is_admin');


//Map Routes

Route::get('getPotholesGrid/{grid}', [PotholeController::class, 'getGrid']);
Route::get('addPothole/{lat},{lng},{user},{grid}', [PotholeController::class, 'add']); // TODO: change to POST?
Route::get('addPotholeExisisting/{id},{user}', [PotholeController::class, 'addExisisting']); // TODO: change to POST?
Route::get('removePothole/{id},{user}', [PotholeController::class, 'removePothole']);

Route::get('getRoadblocksGrid/{grid}', [RoadblockController::class, 'getGrid']);
Route::get('addRoadblock/{lat},{lng},{user},{grid}', [RoadblockController::class, 'add'])->middleware('is_admin');
Route::get('removeRoadblock/{id}', [RoadblockController::class, 'remove'])->middleware('is_admin');

Route::get('getHelpGrid/{grid}', [HelpController::class, 'getGrid']);
Route::get('addHelp/{lat},{lng},{user},{grid}', [HelpController::class, 'add']);
Route::get('removeHelp/{id},{user}', [HelpController::class, 'remove']);

