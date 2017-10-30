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
