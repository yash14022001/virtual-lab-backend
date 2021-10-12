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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'StudentController@login');
    Route::post('/register', 'StudentController@register');
    Route::post('/forgot-password', 'StudentController@forgotPassword');
    Route::post('/logout', 'StudentController@logout')->middleware('auth:api');

    Route::post('/university', 'StudentAPIController@university');
    Route::post('/department', 'StudentAPIController@departmentFromUniversityId')->middleware('auth:api');
    Route::post('/subject', 'StudentAPIController@subjectFromDepartmentId')->middleware('auth:api');

    Route::post('/practical-all', 'StudentAPIController@allPracticalFromSubjectId')->middleware('auth:api');
    Route::post('/practical', 'StudentAPIController@practicalFromPracticalId')->middleware('auth:api');

    Route::post('/post-inquiry', 'StudentAPIController@postInquiry')->middleware('auth:api');

});
