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

Route::resource('users', 'UserController');

Route::get('authors/search', 'AuthorController@search')->name('authors.search');
Route::resource('authors', 'AuthorController');

Route::get('papers/search', 'PaperController@search')->name('papers.search');
Route::resource('papers', 'PaperController');

Route::resource('authorPapers', 'AuthorPaperController');

Route::resource('coAuthors', 'CoAuthorController');

Route::get('candidates/search', 'CandidateController@search')->name('candidates.search');
Route::resource('candidates', 'CandidateController');

Route::resource('universities', 'UniversityController');

Route::resource('cities', 'CityController');

Route::resource('countries', 'CountryController');
