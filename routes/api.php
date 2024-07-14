<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\PlantController;
use App\Http\Controllers\Api\V1\StripePaymentController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ForgetPasswordController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\V1\SocialiteController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["namespace"=>"Api"],function (){
    Route::post("login",[AuthController::class,'login']);
    Route::post("register",[AuthController::class,'register']);

    Route::group(["middleware"=>'verify.token'],function (){
        Route::post("logout",[AuthController::class,'logout']);
        Route::post("refresh",[AuthController::class,'refresh']);
    });

    Route::post('password/forget_password',[ForgetPasswordController::class,'forgetPassword']);
    Route::post('password/reset_password',[ResetPasswordController::class,'resetPassword']);
    Route::post('password/otp_password',[ResetPasswordController::class,'sendOTP']);

    //sign up with Google

    Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);


    //post
    Route::post('create_post',[PostController::class,'store']); //create_post
    Route::get('get_posts',[PostController::class,'index']); //all images
    Route::get('/posts',[PostController::class,'show']); //specific_post
    Route::put('/update_post',[PostController::class,'update']);  //edit_post
    Route::delete('/delete_post', [PostController::class, 'destroy']); //delete_post



    // Comment
    Route::get('/posts/comments', [CommentController::class, 'index']); // all comments of a post
    Route::post('/posts/create_comments', [CommentController::class, 'store']); // create comment on a post
    Route::put('posts/update_comments', [CommentController::class, 'update']); // update a comment
    Route::delete('/comments', [CommentController::class, 'destroy']); // delete a comment





    Route::get('profile',[UserController::class,'profile']);
    Route::patch('update_profile',[UserController::class,'updateProfile']);
    Route::post('update_image',[UserController::class,'updateImage']);

    Route::get('/categories',[CategoryController::class,'index']);
    Route::get('/categories',[CategoryController::class,'show']);
    Route::get('/AllCategories',[categoryController::class,'getAllCategoriesWithPlants']);

    Route::get('plants',[PlantController::class,'showAll']);
    Route::get('plants/popular',[PlantController::class,'popularPlant']);
    Route::get('search',[PlantController::class,'search']);
    Route::post('plants/toggle_plant',[PlantController::class,'togglePlant']);
    Route::get('favourite_plants',[PlantController::class,'favouritePlants']);

    Route::post('stripe',[StripePaymentController::class,'stripePost']);















});
