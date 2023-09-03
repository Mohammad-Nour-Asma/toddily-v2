<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdminAccounts;
use App\Http\Controllers\AgeSectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildCourseController;
use App\Http\Controllers\ChildCourseStatusController;
use App\Http\Controllers\ChildImagesController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\ChildrenStatusController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventImagesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionAndAnswerController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SubstatusController;
use App\Models\Substatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Public
Route::post('/login' , [AuthController::class , 'login']);
Route::apiResource('events' , EventController::class)->only(['index' , 'show']);
Route::apiResource('post' , PostController::class)->only(['index']);
Route::apiResource('question-answer' , QuestionAndAnswerController::class)->only(['index']);

// Protected Routes

Route::group(['middleware' => ['auth:sanctum']] , function (){
    Route::post('/logout' , [AuthController::class , 'logout']);
    Route::get('/current-user' , [AuthController::class , 'getCurrentUser']);
    Route::get('/status-dates/{id}' , [ChildrenStatusController::class , 'getStatusDates']);



    Route::middleware(['admin'])->group(function () {

        Route::apiResource('status' , StatusController::class)->except(['show']);
        Route::apiResource('substatus' , SubstatusController::class)->except(['show','index']);
        Route::apiResource('age-section' , AgeSectionController::class)->except(['show']);

        Route::apiResource('classroom' , ClassRoomController::class)->except(['show']);

        Route::apiResource('accounts' , AccountsController::class);
        Route::put('accounts/reset-password/{id}' , [AccountsController::class , 'resetPassword']);

        Route::apiResource('roles' , RoleController::class)->only(['index']);

        //Add Events
        Route::apiResource('events' , EventController::class)->except(['index' , 'show']);

        //Events Images
        Route::apiResource('event-images' , EventImagesController::class)->except(['index' , 'show']);

        // Posts
        Route::apiResource('post' , PostController::class)->except(['index' , 'show']);

        //Q&A
        Route::apiResource('question-answer' , QuestionAndAnswerController::class)->except(['index' , 'show']);
        Route::apiResource('children' , ChildrenController::class);

        Route::apiResource('course' , CourseController::class);
        Route::apiResource('child-course' , ChildCourseController::class)->only('store','destroy');

    });



    Route::middleware(['teacher'])->group(function () {

        Route::apiResource('child-status' , ChildrenStatusController::class);
        Route::apiResource('child-course-status' , ChildCourseStatusController::class)->only(['store','destroy']);

        Route::get('/get-child-status/{id}' , [ChildrenController::class , 'getStatusChildren']);
        Route::get('/child-images/{id}' , [ChildImagesController::class , 'getChildImages']);
        Route::put('/image-check/{id}' , [ChildImagesController::class , 'makeImageChecked']);
    });


    Route::middleware(['doctor'])->group(function () {
        Route::apiResource('reports' , ReportsController::class);
    });

    Route::middleware(['social'])->group(function () {
        Route::apiResource('child-image' , ChildImagesController::class)->except(['show','index','update','destroy']);
    });

    Route::middleware(['teacherSocial'])->group(function () {
        Route::apiResource('child-image' , ChildImagesController::class)->only(['destroy']);
    });

    Route::apiResource('classroom' , ClassRoomController::class)->only(['show']);
    Route::get('/get_child_reports/{id}' , [ReportsController::class , 'childReports']);

    Route::post('get-child-status-date/{id}' , [ChildrenStatusController::class , 'getStatusByDate']);
    Route::get('get-child-images/{id}' , [ChildImagesController::class , 'getChildImagesForParents']);

});
