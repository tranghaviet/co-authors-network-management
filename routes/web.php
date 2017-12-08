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
Route::get('authors/search', 'AuthorController@search')->name('user.authors.search');
Route::get('authorPapers/search', 'AuthorPaperController@search')->name('user.authorPaper.search');
Route::get('papers/search', 'PaperController@search')->name('user.papers.search');
Route::get('coAuthors/search', 'CoAuthorController@search')->name('user.coAuthors.search');
Route::get('candidates/search', 'CandidateController@search')->name('user.candidates.search');
Route::get('universities/search', 'UniversityController@search')->name('user.universities.search');

Route::get('authors', 'AuthorController@index')->name('user.authors.index');
Route::get('authors/{id}', 'AuthorController@show')->name('user.authors.show');

Route::get('papers', 'PaperController@index')->name('user.papers.index');
Route::get('papers/{id}', 'PaperController@show')->name('user.papers.show');

Route::get('authorPapers', 'AuthorPaperController@index')->name('user.author-paper.index');

Route::get('coAuthors', 'CoAuthorController@index')->name('user.co-authors.index');

Route::get('candidates', 'CandidateController@index')->name('user.candidates.index');

Route::get('universities', 'UniversityController@index')->name('user.universities.index');
Route::get('universities/{id}', 'UniversityController@show')->name('user.universities.show');

Route::group(['prefix' => 'admin/', 'middleware' => 'auth'], function () {
    Route::get('authors/search', 'AuthorController@search')->name('authors.search');
    Route::get('authorPapers/search', 'AuthorPaperController@search')->name('authoPaper.search');
    Route::get('papers/search', 'PaperController@search')->name('papers.search');
    Route::get('coAuthors/search', 'CoAuthorController@search')->name('coAuthors.search');
    Route::get('candidates/search', 'CandidateController@search')->name('candidates.search');
    Route::get('universities/search', 'UniversityController@search')->name('universities.search');

    Route::resource('users', 'UserController');

    Route::resource('authors', 'AuthorController');

    Route::resource('papers', 'PaperController');

    Route::resource('authorPapers', 'AuthorPaperController');

    Route::resource('coAuthors', 'CoAuthorController');

    Route::resource('candidates', 'CandidateController');

    Route::resource('universities', 'UniversityController');

    Route::resource('cities', 'CityController');

    Route::resource('countries', 'CountryController');

    Route::get('sync', 'SyncController@index')->name('sync.index');
    Route::post('sync', 'SyncController@active')->name('sync.active');
});

/*
 *  Write test import logic in here, for example:
 *  $papers = fgetcsv(...);
 *  handle_keywords($papers[0]['id], $papers[0]['keywords'];
 *  to show data use dump($variable) (will show data and continue run program)
 *  or dd($variable) (will show data end end the program).
 */
use App\Models\CoAuthor;
use App\Models\Author;
Route::get('/test_import', function () {
    $first_author_id = 65038471460;
    $second_author_id = 7006831047;
    // Your code here
//    $a = CoAuthor::where([
//        'first_author_id' => $first_author_id,
//        'second_author_id' => $second_author_id,
//    ])
//        ->orWhere([
//            'first_author_id' => $second_author_id,
//            'second_author_id' => $first_author_id,
//        ])->first();
//    dd($a);
    Author::orderBy('id')->limit(50)->chunk(10, function ($authors) {
        dump($authors);
    });
});

Route::get('/view_upload_papers',['as'=>'view_upload_papers','uses'=>'ImportPaperController@view_upload_papers']);
Route::post('/upload_papers',['as'=>'upload_papers','uses'=>'ImportPaperController@upload_papers']);

// route upload authors
Route::get('/view_upload_authors',['as'=>'view_upload_authors','uses'=>'ImportAuthorController@view_upload_authors']);
Route::post('/upload_authors',['as'=>'upload_authors','uses'=>'ImportAuthorController@upload_authors']);

//route upload authors_papers
Route::get('/view_upload_authors_papers',['as'=>'view_upload_authors_papers','uses'=>'ImportAuthor_PaperController@view_upload_authors_papers']);
Route::post('/upload_authors_papers',['as'=>'upload_authors_papers','uses'=>'ImportAuthor_PaperController@upload_authors_papers']);

Route::get('/test',['as'=>'test','uses'=>'TestProcessController@testprocess']);
