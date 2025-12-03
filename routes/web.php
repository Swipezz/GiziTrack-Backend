<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Models\Account;
use App\Models\Employee;

use App\Http\Controllers\API\AuthAPI;
use App\Http\Controllers\API\ProfileAPI;
use App\Http\Controllers\API\SchoolAPI;
use App\Http\Controllers\API\SurveyAPI;

Route::get('/', fn() => view('AIO'));

Route::post('/api/login', [AuthAPI::class, 'RequestLogin']);
Route::post('/api/register', [AuthAPI::class, 'RequestRegister']);
Route::post('/api/logout', [AuthAPI::class, 'RequestLogout']);

Route::get('/api/profile', [ProfileAPI::class, 'GetProfile']);

Route::get('/api/school', [SchoolAPI::class, 'GetListSchool']);
Route::post('/api/school', [SchoolAPI::class, 'InsertSchool']);
Route::get('/api/school/{id}', [SchoolAPI::class, 'GetDetailSchool']);
Route::put('/api/school/{id}', [SchoolAPI::class, 'UpdateSchool']);
Route::delete('/api/school/{id}', [SchoolAPI::class, 'DeleteSchool']);

Route::post('/api/survey/food', [SurveyAPI::class, 'SurveyFood']);
Route::post('/api/survey/allergy', [SurveyAPI::class, 'SurveyAllergy']);