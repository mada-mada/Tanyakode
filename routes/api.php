<?php
use App\Http\Controllers\superadmin\Adminsekolahcontroller;
use App\Http\Controllers\superadmin\SchoolAdminController;
use App\Http\Controllers\superadmin\SchoolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\Superadmin_sekolahcontroller;
use App\Http\Controllers\superadmin\Superadmincontroller;

Route::post('/superadmin/admins', [Superadmincontroller::class, 'store']);
Route::get('/superadmin/admins', [Superadmincontroller::class, 'index']);
Route::put('/superadmin/admins/{id}', [Superadmincontroller::class, 'update']);
Route::delete('/superadmin/admins/{id}', [Superadmincontroller::class, 'destroy']);


Route::get('/superadmin/schools', [Superadmin_sekolahcontroller::class, 'index']);
Route::post('/superadmin/schools', [Superadmin_sekolahcontroller::class, 'store']);
Route::get('/superadmin/schools/{id}', [Superadmin_sekolahcontroller::class, 'show']);
Route::put('/superadmin/schools/{id}', [Superadmin_sekolahcontroller::class, 'update']);
Route::delete('/superadmin/schools/{id}', [Superadmin_sekolahcontroller::class, 'destroy']);

Route::get('/superadmin/school-admins', [Adminsekolahcontroller::class, 'index']);
Route::post('/superadmin/school-admins', [Adminsekolahcontroller::class, 'store']);
Route::get('/superadmin/school-admins/{id}', [Adminsekolahcontroller::class, 'show']);
Route::put('/superadmin/school-admins/{id}', [Adminsekolahcontroller::class, 'update']);
Route::delete('/superadmin/school-admins/{id}', [Adminsekolahcontroller::class, 'destroy']);
