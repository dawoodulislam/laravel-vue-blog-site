<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
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

Route::get('/new', [TestController::class, 'index']);

// Tag
Route::post('/app/create_tag', [AdminController::class, 'addTag']);
Route::post('/app/edit_tag', [AdminController::class, 'editTag']);
Route::post('/app/delete_tag', [AdminController::class, 'deleteTag']);
Route::get('/app/get_tags', [AdminController::class, 'getTag']);

// category 
Route::post('/app/upload', [AdminController::class, 'uploadCategory']);
Route::post('/app/delete_image', [AdminController::class, 'deleteImage']);
Route::post('/app/create_category', [AdminController::class, 'addCategory']);
Route::post('/app/edit_category', [AdminController::class, 'editCategory']);
Route::get('/app/get_categories', [AdminController::class, 'getCategory']);
Route::post('/app/delete_category', [AdminController::class, 'deleteCategory']);

//admin user
Route::post('/app/create_users', [AdminController::class, 'addAdminUser']);
Route::get('/app/get_users', [AdminController::class, 'getUsers']);
Route::post('/app/edit_user', [AdminController::class, 'editUser']);

Route::post('/app/admin_login', [AdminController::class, 'adminLogin']);

Route::any('{slug}', function () {
    return view('welcome');
});
