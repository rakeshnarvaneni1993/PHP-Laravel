
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// ->middleware('auth-jwt')

 Route::post('authenticate', 'AuthenticateController@authenticate');

Route::get('posts', 'ExampleController@index'); //Beads Table

Route::get('beads-articles', 'BeadsArticleController@index')->middleware('jwt.auth');

Route::get('course-data', 'CourseDataController@index'); //Data of all the courses and chapters

Route::patch('put-course-data', 'PutCourseDataController@index');
// ->middleware('cors'); //Routo for put request
Route::patch('put-topic-data', 'UpdateTopicsController@index');

Route::patch('put-beads-data', 'UpdateBeadsController@index'); 

Route::patch('put-beads-problems-data', 'UpdateBeadsProblemsController@index'); 




Route::get('beads/{id}', 'BeadsJoinController@index'); //Data related to Beads with the ID

Route::get('beads-examples', 'BeadsExampleController@index'); 

Route::get('beads-problems', 'BeadsProblems@index');


Route::get('chapters', 'ChaptersController@index');

Route::get('courses', 'ChaptersController@index');

Route::get('authors', 'AuthorController@index');


//**************************************FOR KW COURSES *****************************************

Route::get('kw-beads/{id}', 'KWBeadsController@index'); //Data related to Beads with the ID

Route::get('kw-course-data', 'KWCourseDataController@index'); //Data of all the courses and chapters



// Route::get('/posts', 'ExampleController@index'); //Beads Table

// Route::get('/beads-articles', 'BeadsArticleController@index');

// Route::get('/course-data', 'CourseDataController@index'); //Data of all the courses and chapters

// Route::patch('/put-course-data', 'PutCourseDataController@index');
// // ->middleware('cors'); //Routo for put request
// Route::patch('/put-topic-data', 'UpdateTopicsController@index');

// Route::patch('/put-beads-data', 'UpdateBeadsController@index'); 

// Route::patch('/put-beads-problems-data', 'UpdateBeadsProblemsController@index'); 




// Route::get('/beads/{id}', 'BeadsJoinController@index'); //Data related to Beads with the ID

// Route::get('/beads-examples', 'BeadsExampleController@index'); 

// Route::get('/beads-problems', 'BeadsProblems@index');


// Route::get('/chapters', 'ChaptersController@index');

// Route::get('/courses', 'ChaptersController@index');

// Route::get('/authors', 'AuthorController@index');


// //**************************************FOR KW COURSES *****************************************

// Route::get('/kw-beads/{id}', 'KWBeadsController@index'); //Data related to Beads with the ID

// Route::get('/kw-course-data', 'KWCourseDataController@index'); //Data of all the courses and chapters
