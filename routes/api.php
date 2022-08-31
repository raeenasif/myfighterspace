<?php

//header('Content-Type: text/html; charset=utf-8');
//header('Access-Control-Allow-Origin: *');
//Access-Control-Allow-Origin: *
//header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
//header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserFollowController;
use App\Http\Controllers\Api\OnboardingController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\MembershipController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', [AuthController::class, 'login']); /* Working Properly */
Route::post('/signup', [AuthController::class, 'signup']);  /* Working Properly */
Route::post('/resend/otp', [AuthController::class, 'reSendOTP']); /* Working Properly */
Route::post('/verify/otp', [AuthController::class, 'verifyOTP']); /* Working Properly */

// Route::post('/register/screen/{screen}', [\App\Http\Controllers\Api\AuthController::class, 'store']);

Route::post('/password/email', [AuthController::class, 'forgot']); /* Working Properly */
Route::post('password/reset',  [AuthController::class, 'reset']); /* Working Properly */
// Route::post('/welcome/screen/status/update', [AuthController::class, 'welcomeScreenStatusUpdate']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/edit-profile', [UserController::class, 'edit']);
    Route::get('/all-user', [UserController::class, 'alluser']);

    //  post API 

    Route::post('/upload-post', [PostController::class, 'upload']);
    Route::post('/delete-post', [PostController::class, 'delete']);
    Route::get('/all-post', [PostController::class, 'index']);
    Route::post('/single-user', [UserController::class, 'singleuser']);
    Route::get('/user-post', [PostController::class, 'userpost']);
    Route::post('/like-post', [PostController::class, 'likepost']);

    // COMMENT POST
    Route::post('/comment-post', [CommentController::class, 'store']);
    Route::post('/delete-comment', [CommentController::class, 'destroy']);

    //like post user
    Route::post('/likepost-user', [UserController::class, 'likePostUser']);
    Route::post('/likecomment-user', [UserController::class, 'likeCommentUser']);

    Route::post('/like-comment', [CommentController::class, 'likeComment']);
    Route::post('/likecomment-count', [CommentController::class, 'likeCommentCount']);


    // End Post API


    // Start  Followers ans following API
    Route::post('/follow', [UserFollowController::class, 'followRequest']); /* both api follow and unfollow work with in this API */
    Route::get('/get-followrequest', [UserFollowController::class, 'getFollowRequest']);
    Route::post('/request-status', [UserFollowController::class, 'requestStatus']);

    Route::get('/get-userfollowers', [UserFollowController::class, 'userFollowers']);
    Route::get('/get-userfollowing', [UserFollowController::class, 'userFollowing']);
    // End  Followers ans following API 



});
