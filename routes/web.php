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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

Route::get('/', function () {
    $links = \App\Link::all();
    session()->regenerate();
    $word = DB::table('words_common')->inRandomOrder()->select('words')->get()->first();
    $scrambled_word = str_shuffle($word->words);
    $encrypted_word = Crypt::encryptString($word->words);
    return view('welcome', ['links' => $links, 
			    'word' => $scrambled_word, 
			    'original_word' => $word->words , 
			    'encrypted_word' => $encrypted_word ]);
});


Route::get('/scrambler', 'ScrambleController@get_word');

Route::post('/scrambler','ScrambleController@submit_word');

Route::post('/skip','ScrambleController@skip');

Route::get('/display_score','ScrambleController@display_score');

Route::get('/getfinalscore','ScrambleController@getfinalscore');

Route::get('/gethighscore','ScrambleController@highscore');

Route::post('/tellname','ScrambleController@tellname');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/submit', function () {
    return view('submit');
});

Route::post('/submit', function (Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'url' => 'required|url|max:255',
        'description' => 'required|max:255',
    ]);

    $link = tap(new App\Link($data))->save();

    return redirect('/');
});
