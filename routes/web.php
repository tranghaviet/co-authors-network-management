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

Route::get('/', function () {
    return view('welcome');
});
// TODO: add prefix 'admin'
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// TODO: return different view for admin and user, do it in controller.
Route::get('authors/search', 'AuthorController@search')->name('authors.search');
Route::get('papers/search', 'PaperController@search')->name('papers.search');
Route::get('coAuthors/search', 'CoAuthorController@search')->name('coAuthors.search');
Route::get('candidates/search', 'CandidateController@search')->name('candidates.search');
Route::get('universities/search', 'UniversityController@search')->name('universities.search');

Route::group(['prefix' => 'admin/', 'middleware' => 'auth'], function () {
    Route::resource('users', 'UserController');

    Route::resource('authors', 'AuthorController');

    Route::resource('papers', 'PaperController');

    Route::resource('authorPapers', 'AuthorPaperController');

    Route::resource('coAuthors', 'CoAuthorController');

    Route::resource('candidates', 'CandidateController');

    Route::resource('universities', 'UniversityController');

    Route::resource('cities', 'CityController');

    Route::resource('countries', 'CountryController');
});

// route upload papers
Route::get('/view_upload_papers',['as'=>'view_upload_papers','uses'=>'ImportPaperController@view_upload_papers']);
Route::post('/upload_papers',['as'=>'upload_papers','uses'=>'ImportPaperController@upload_papers']);

// route upload authors
Route::get('/view_upload_authors',['as'=>'view_upload_authors','uses'=>'ImportAuthorController@view_upload_authors']);
Route::post('/upload_authors',['as'=>'upload_authors','uses'=>'ImportAuthorController@upload_authors']);

//route upload authors_papers
Route::get('/view_upload_authors_papers',['as'=>'view_upload_authors_papers','uses'=>'ImportAuthor_PaperController@view_upload_authors_papers']);
Route::post('/upload_authors_papers',['as'=>'upload_authors_papers','uses'=>'ImportAuthor_PaperController@upload_authors_papers']);
