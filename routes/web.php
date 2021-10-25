<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// FrontEnd Section of Routes

// Route::get('/', 'HomeController@articlesIndex');
Route::get('/', 'HomeController@index');

// Home Page
Route::get('/home', 'HomeController@articlesIndex')->name('home');


// Related articles Page
// Route::get('/articles/{article}', 'HomeController@singleArticle')->name('article.show');

Route::get('/singlecontent/{id}/{slug}', 'HomeController@singleContent')->name('single.content'); 



Route::post('/singlecontent/{id}/comments', 'HomeController@addComment')->name('single.comment'); 
Route::post('/singlecontent/{id}/like', 'HomeController@concatLike'); 
Route::post('/singlecontent/{id}/unlike', 'HomeController@deleteClap'); 

// comment like/unlike
Route::post('/comment/{comment}/like', 'HomeController@addLikeComment')->name('comment.like'); 
Route::post('/comment/{comment}/unlike', 'HomeController@removeLikeComment')->name('comment.unlike'); 

// Route::get('/singlecontent/{id}/like/{id}', ['as' => 'product.like', 'uses' => 'HomeController@likeProduct']);
// Route::get('/singlecontent/{id}/comments/{id}', 'HomeController@likeProduct');
// Route::get('/singlecontent/{id}/like', 'HomeController@likeProduct');

Route::get('/come-nasce-agora-salute', function () {
    return view('frontend.partials.comenato');
})->name('come.nato');




//best replies
Route::post('/replies/{reply}/best-replies', 'BestRepliesController@store')->name('best-replies.store');

// Search with autocompletion
Route::post('/search', 'HomeController@searchArticles')->name('search-articles');

// Filter Tags
Route::get('/content/tags/{tag}', 'TagsFrontController@index')->name('tag-articles');

//all contents
Route::get('/contenuti', 'ArticleFrontController@allContent')->name('allcontents');

//webinar
Route::get('/webinar/{webinarItem}', 'HomeController@webinar')->name('webinar_article');


Auth::routes();

Route::any('/register', function() {
    return view('auth.login');
});

Route::group(['prefix' => 'admin' ,'middleware' => ['auth', 'admin']], function () {

    Route::get('/', function () {// /admin
        return view('admin.dashboard');
    })->name('admin');

    Route::get('/dashboard', function () { // /admin/dashboard
        return view('admin.dashboard');
    })->name('dashboard');

  

    // Route::get('/home', 'HomeController@articlesIndex')->name('home');

   // user roles
   Route::get('/role-register', 'Admin\DashboardController@registered')->name('tipi.utenti');
   Route::get('/role-create', 'Admin\DashboardController@createUser')->name('tipi.create');
   Route::get('/role-edit/{id}', 'Admin\DashboardController@registeredit')->name('tipi.edit');
   Route::put('/role-register-update/{id}', 'Admin\DashboardController@registerupdate')->name('tipi.update');
   Route::post('/role-store', 'Admin\DashboardController@storeUser')->name('tipi.store');
   Route::delete('role-delete/{id}', 'Admin\DashboardController@registerdelete')->name('tipi.delete');


   Route::get('/roleeditpass/{user}', 'Admin\DashboardController@editPsw')->name('rolepass.edit');
   Route::put('/roleeditpass/{user}', 'Admin\DashboardController@updatePsw')->name('rolepass.update');
   
   Route::get('/utente/invite', 'Admin\DashboardController@invite_view')->name('invite_view');
   Route::post('/utente/invite', 'Admin\DashboardController@process_invites')->name('process_invite');

   Route::get('accept/{token}', 'InviteController@accept')->name('accept');

   // category
   Route::get('/content-category', 'Admin\ServiceController@index')->name('category.index');
   Route::get('/content-create', 'Admin\ServiceController@create')->name('category.create');
   Route::post('/categorystore', 'Admin\ServiceController@store')->name('category.save');
   Route::get('/category-edit/{category}', 'Admin\ServiceController@edit')->name('category.edit');
   Route::put('/category-update/{category}', 'Admin\ServiceController@update')->name('category.update');
   Route::delete('/category-delete/{category}', 'Admin\ServiceController@delete')->name('category.delete');

   // tags
   Route::get('/tags-filter', 'Admin\TagsController@index')->name('dashboard.tagsindex');
   Route::get('/tags-create', 'Admin\TagsController@create')->name('tag.create');
   Route::post('/tags-store', 'Admin\TagsController@store')->name('tag.store');
   Route::get('/tags-cat-edit/{tag}', 'Admin\TagsController@edit')->name('tag.edit');
   Route::put('/tags-update/{tag}', 'Admin\TagsController@update')->name('tag.update');
   Route::delete('/tags-delete/{tag}', 'Admin\TagsController@destroy')->name('tag.delete');

    // multimedia
    Route::get('/content-multimedia', 'Admin\MultimediasController@index')->name('multimedia.index');
    Route::post('/content-multi-add', 'Admin\MultimediasController@store')->name('multimedia.store');
    Route::get('/content-list-create', 'Admin\MultimediasController@create')->name('contentlist.create');
    Route::get('/content-list-edit/{multimedia}', 'Admin\MultimediasController@editMultimedia')->name('contentlist.edit');
    Route::put('/content-list-update/{multimedia}', 'Admin\MultimediasController@update')->name('contentlist.update');
    Route::delete('/content-list-delete/{multimedia}', 'Admin\MultimediasController@delete')->name('contentlist.delete');


   // video
   Route::get('/tuttelevideo', 'Admin\VideosController@index')->name('video.index');
   Route::get('/video-creazione', 'Admin\VideosController@create')->name('video.create');
   Route::post('/video-store', 'Admin\VideosController@store')->name('video.store');
   Route::get('/videoedit/{video}', 'Admin\VideosController@edited')->name('video.edit');
   Route::put('/video-update/{video}', 'Admin\VideosController@update')->name('video.update');
   Route::delete('/video-delete/{video}', 'Admin\VideosController@destroy')->name('video.delete');

   // articles
   Route::get('/articles-index', 'Admin\ArticlesController@index')->name('articles.index');
   Route::get('/article-create', 'Admin\ArticlesController@create')->name('articles.create');
   Route::post('/article-store', 'Admin\ArticlesController@store')->name('article.store');
   Route::post('/article-store/upload', 'Admin\ArticlesController@uploadFoto')->name('article.upload');
   Route::get('/articles-edit/{article}', 'Admin\ArticlesController@edit')->name('article.edit');
   Route::put('/articles-update/{article}', 'Admin\ArticlesController@update')->name('article.update');
   Route::delete('/articles/{id}', 'Admin\ArticlesController@delete')->name('article.delete');


   Route::get('/articles/action','ArticleFrontController@action')->name('articles.action');



   // content
   Route::get('/videos/{video}', 'HomeController@video')->name('video.show');
   Route::get('/podcast/{multimediaPodcast}', 'HomeController@podcast')->name('podcasthome.show');
   Route::get('/podcast/{multimediaPodcast}/claps', 'HomeController@podcastClap');
   Route::get('/home/webinar/{id}', 'HomeController@webinar')->name('webinar_reply');


   // webinar
   Route::get('/webinar-index', 'Admin\WebinarController@index')->name('webinar.index');
   Route::get('/webinar-create', 'Admin\WebinarController@create')->name('webinar.create');;
   Route::post('/webinar-store', 'Admin\WebinarController@store')->name('webinar.store');
   Route::get('/webinar-edit/{webinar}', 'Admin\WebinarController@edit')->name('webinar.edit');
   Route::put('/webinar-update/{webinar}', 'Admin\WebinarController@update')->name('webinar.update');
   Route::delete('/webinar-delete/{webinar}', 'Admin\WebinarController@delete')->name('webinar.delete');

});


// -- Forum Routes --
Route::get('/threads', 'ThreadsController@index')->name('threads.index');
Route::get('/threads/{thread}', 'ThreadsController@show')->name('threads.show');
Route::post('threads/store', 'ThreadsController@store')->name('threads.store');
Route::post('/threads/{thread}/replies', 'ReplyController@store');
Route::post('/threads/{thread}/likes', 'ReplyController@comment_likes')->name('threads.likes');
//likes threads
Route::get('thread/like/{id}', ['as' => 'thread.like', 'uses' => 'ReplyController@likePost']);
// likes
Route::get('thread/like/{id}', ['as' => 'thread.like', 'uses' => 'ThreadsController@likePost']);



Route::delete('/threads/{thread}/delete', 'ThreadsController@delete')->name('threads.delete');

Route::get('/threads/{id}/update_view', 'ThreadsController@update_view');
Route::put('/threads/{id}/update', 'ThreadsController@update');

Route::get('/drafts', 'ThreadsController@drafts');

Route::put('/publish', 'ThreadsController@publish')->name('threads.publish');
Route::put('/un_publish', 'ThreadsController@un_publish')->name('threads.un_publish');


// -- Medikey Site Routes --
Route::get('/medikey', 'MedikeyController@index')->name('medikey.index');
Route::post('/medikey/store', 'MedikeyController@store')->name('medikey.store');


Route::get('/medikey/medlogin', 'MedikeyController@medLogin')->name('medikey.medlogin');
Route::get('/medikey/medlogout', 'MedikeyController@ticketLogout')->name('medikey.medlogout');
// -- MEDIKEY RETURN PAGE --
Route::get('/medikey/ticket_validate', 'MedikeyController@returnTicketValidate')->name('medikey.ticket_validate');
Route::get('/medikey/medikey_profile', 'MedikeyController@medikeyProfile')->name('medikey.medikey_profile');
Route::post('/meduser/store', 'MedUserController@store')->name('meduser.store');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('user.logout');

//webinar comments
Route::resource('/comments','CommentsController');
Route::post('/webinarreply','CommentsController@replywebinar')->name('rreply_comments.replywebinar');
Route::post('/webinar/second/reply','CommentsController@replySecwebinar')->name('reply_sec_webinars.replySecwebinar');
Route::get('webinar/like/{id}', ['as' => 'reply.like', 'uses' => 'CommentsController@likePost']);
Route::get('reply_second/like/{id}', ['as' => 'reply_second_webinar.like', 'uses' => 'CommentsController@likeSecondReply']);
Route::post('/webinar/third/reply','CommentsController@replyThirdWebinar')->name('webinar_third_comments.replyThirdWebinar');
//article comments
Route::post('/articlecomment','ArticleCommentController@store')->name('comments_article.store');
Route::post('/articlereply','ArticleCommentController@replyarticle')->name('reply_article.replyarticle');
Route::post('/article/second/reply','ArticleCommentController@replySecArticle')->name('reply_sec_articles.replySecArticle');
Route::get('articles/like/{id}', ['as' => 'replys.like', 'uses' => 'ArticleCommentController@likeArticle']);
//video comments
Route::post('/videocomments','CommentVideoController@store')->name('comments_video.store');
Route::post('/videoreply','CommentVideoController@replyvideo')->name('reply_video.replyvideo');
Route::post('/video/second/reply','CommentVideoController@replySecVideo')->name('reply_sec_videos.replySecVideo');
Route::get('videos/like/{id}', ['as' => 'reply_video.like', 'uses' => 'CommentVideoController@likeVideo']);
//podcast comments
Route::post('/podcastcomments','PodcastCommentController@store')->name('comments_podcast.store');
Route::post('/podcastreply','PodcastCommentController@replypodcast')->name('reply_podcast.replypodcast');
Route::post('/podcast/second/reply','PodcastCommentController@replySecPodcast')->name('reply_sec_podcast.replySecPodcast');
Route::get('podcasts/like/{id}', ['as' => 'reply_podcast.like', 'uses' => 'PodcastCommentController@likePodcast']);
//likes comments

Route::get('post/like/{id}', ['as' => 'post.like', 'uses' => 'LikeController@likePost']);
Route::get('video/like/{id}', ['as' => 'video.like', 'uses' => 'LikeVideoController@likePost']);
Route::get('article/like/{id}', ['as' => 'article.like', 'uses' => 'LikeArticleController@likePost']);
Route::get('podcast/like/{id}', ['as' => 'podcast.like', 'uses' => 'LikePodcastController@likePost']);

// dumb cashe

Route::get('singlearticle/like/{id}', ['as' => 'single_article.like', 'uses' => 'Admin\ArticlesController@likePost']);
Route::get('singlevideo/like/{id}', ['as' => 'single_video.like', 'uses' => 'Admin\MultimediasController@likePost']);
Route::get('singlepodcast/like/{id}', ['as' => 'single_podcast.like', 'uses' => 'ArticleFrontController@likePost']);
Route::get('singlewebinar/like/{id}', ['as' => 'single_webinar.like', 'uses' => 'Admin\WebinarController@likePost']);
// Route::get('/clear-cache', function() {
//     Artisan::call('cache:clear');
//     return "Cache is cleared";
// });

// Route::get('/foo', function () {
//     Artisan::call('storage:link');
// });
Route::get('/privacy','FooterController@privacy')->name('privacy');
Route::get('/Medikey','FooterController@medikey')->name('credits');
Route::get('/note_legali','FooterController@legali')->name('note_legali');

//GR 25/09/2021
Route::get('/admin/login/', '\App\Http\Controllers\Auth\LoginbeController@loginbe')->name('admin.login');
