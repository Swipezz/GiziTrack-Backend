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

// Semua API dipanggil menggunakan fetch()

// Public API routes (no authentication required)
// User menginput username & password setelah itu server memberikan user_id, username, api_token
Route::post('/api/login', [AuthAPI::class, 'RequestLogin']);
// User menginput username, password, office, employee(int) setelah itu server memberikan id, username, office, employee
Route::post('/api/register', [AuthAPI::class, 'RequestRegister']);

// User memerlukan api_token untuk mengakses API dibawah
// Protected API routes (require token authentication)
Route::middleware(['api.token'])->group(function () {
    // User hanya perlu melakukan request dan session api_token akan dihapus 
    Route::post('/api/logout', [AuthAPI::class, 'RequestLogout']);

    // Memberikan tabel user
    //     "data": {
    //     "id": 1,
    //     "username": "lorem",
    //     "password": "lorem",
    //     "api_token": "lorem",
    //     "office": "lorem",
    //     "employee": 2,
    //     "created_at": lorem,
    //     "updated_at": lorem,
    //     "employees": [1,2]
    // }
    Route::get('/api/profile', [ProfileAPI::class, 'GetProfile']);

    //     {
    //     "id": 3,
    //     "logo": "lorem",
    //     "name": "lorem",
    //     "location": "lorem",
    //     "total_meal": 3
    // },
    Route::get('/api/school', [SchoolAPI::class, 'GetListSchool']);

    // User perlu mengirim name, location, total_student(int), total_meal(int), type_allergy, logo
    Route::post('/api/school', [SchoolAPI::class, 'InsertSchool']);
    
    // "id": 1,
    // "logo": "lorem",
    // "name": "lorem",
    // "location": "lorem",
    // "total_student": 1,
    // "total_meal": 2,
    // "type_allergy": "lorem"
    Route::get('/api/school/{id}', [SchoolAPI::class, 'GetDetailSchool']);

    // User perlu mengisi ini
    // "logo": "lorem",
    // "name": "lorem",
    // "location": "lorem",
    // "total_student": 1,
    // "total_meal": 2,
    // "type_allergy": "lorem"
    Route::put('/api/school/{id}', [SchoolAPI::class, 'UpdateSchool']);

    // User hanya perlu mengikuti dibawah ini cukup pake method DELETE dan id diakhir
    Route::delete('/api/school/{id}', [SchoolAPI::class, 'DeleteSchool']);

    // User memilih sekolah yang disediakan dan memberikan data food, total(int)
    Route::post('/api/survey/food', [SurveyAPI::class, 'SurveyFood']);
    // Sama seperti diatas, tapi tidak menggunakan param total
    Route::post('/api/survey/allergy', [SurveyAPI::class, 'SurveyAllergy']);
});