<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

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

// Admin
Route::get('/admins-only', function(){
    // if(Gate::allows('visitAdminPages')){
    //     return 'Only admins can see that message';
    // }
    // return 'you cannot view this page';
    return 'only admins can see this';
})->middleware('can:visitAdminPages');

// User
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware("guest");
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');
// Post
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, "storeNewPost"])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, "viewSinglePost"]);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, "showEditForm"])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, "update"])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, "search"]);

// Profile 
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing'])->middleware('mustBeLoggedIn');

Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
    Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw'])->middleware('mustBeLoggedIn');
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw'])->middleware('mustBeLoggedIn');
    Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw'])->middleware('mustBeLoggedIn');
});

// Follow
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

// Chat
Route::post('/send-chat-message', function(Request $request){
    $fields = $request->validate([
        'textvalue' => 'required'
    ]);
    
    if(!trim(strip_tags($fields['textvalue']))){

        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();
    return response()->noContent();

})->middleware('mustBeLoggedIn');