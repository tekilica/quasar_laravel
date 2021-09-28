<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SharedController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SoundtrackController;

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

Route::get('/', [SharedController::class, 'showHomeView'])
    ->middleware('auth');

Route::get('/login', [SessionController::class, 'showLoginView'])
    ->middleware('guest');

Route::post('/login', [SessionController::class, 'logIn']);

Route::get('/log-out', [SessionController::class, 'logOut']);

Route::get('/users', [UserController::class, 'showUsersView'])
    ->middleware('auth');

Route::get('/create-user', [UserController::class, 'showCreateUserView'])
    ->middleware('auth');

Route::post('/create-user', [UserController::class, 'storeCreatedUser']);

Route::post('/update-users', [UserController::class, 'updateUsers']);

Route::get('/edit-users', [UserController::class, 'showEditUsersView'])
    ->middleware('auth');

Route::post('/edit-users', [UserController::class, 'saveEditedUsers']);

Route::get('/delete-users', [UserController::class, 'deleteUsers'])
    ->middleware('auth');

Route::get('/images', [ImageController::class, 'showImagesView'])
    ->middleware('auth');

Route::get('/upload-images', [ImageController::class, 'showUploadImagesView'])
    ->middleware('auth');

Route::post('/upload-images', [ImageController::class, 'storeUploadedImages']);

Route::get('/image', [ImageController::class, 'showImageView'])
    ->middleware('auth');

Route::post('/image', [ImageController::class, 'downloadImage']);

Route::post('/update-image', [ImageController::class, 'updateImage']);

Route::post('/images', [ImageController::class, 'updateImages']);

Route::get('/edit-images', [ImageController::class, 'showEditImagesView'])
    ->middleware('auth');

Route::post('/edit-images', [ImageController::class, 'saveEditedImages']);

Route::get('/delete-images', [ImageController::class, 'deleteImages'])
    ->middleware('auth');

Route::get('/news', [NewsController::class, 'showNewsView'])
     ->middleware('auth');

Route::get('/videos', [VideoController::class, 'showVideosView'])
    ->middleware('auth');

Route::get('/upload-video', [VideoController::class, 'showUploadVideoView'])
    ->middleware('auth');

Route::post('/upload-video', [VideoController::class, 'storeUploadedVideo']);

Route::get('/video', [VideoController::class, 'showVideoView'])
     ->middleware('auth');

Route::post('/video', [VideoController::class, 'downloadVideo']);

Route::post('/update-video', [VideoController::class, 'updateVideo']);

Route::post('/videos', [VideoController::class, 'updateVideos']);

Route::get('/edit-videos', [VideoController::class, 'showEditVideosView'])
    ->middleware('auth');

Route::post('/edit-videos', [VideoController::class, 'saveEditedVideos']);

Route::get('/delete-videos', [VideoController::class, 'deleteVideos'])
    ->middleware('auth');

Route::get('/soundtracks', [SoundtrackController::class, 'showSoundtracksView'])
     ->middleware('auth');

Route::get('/upload-soundtrack', [SoundtrackController::class, 'showUploadSoundtrackView'])
     ->middleware('auth');

Route::post('/upload-soundtrack', [SoundtrackController::class, 'storeUploadedSoundtrack']);

Route::get('/soundtrack', [SoundtrackController::class, 'showSoundtrackView'])
     ->middleware('auth');

Route::post('/soundtrack', [SoundtrackController::class, 'downloadSoundtrack']);

Route::post('/update-soundtrack', [SoundtrackController::class, 'updateSoundtrack']);

Route::post('/soundtracks', [SoundtrackController::class, 'updateSoundtracks']);

Route::get('/edit-soundtracks', [SoundtrackController::class, 'showEditSoundtracksView'])
     ->middleware('auth');

Route::post('/edit-soundtracks', [SoundtrackController::class, 'saveEditedSoundtracks']);

Route::get('/delete-soundtracks', [SoundtrackController::class, 'deleteSoundtracks'])
     ->middleware('auth');
