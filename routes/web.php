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
    //return view('home');
    $links = \App\Link::all();

//$s1 = 'bokepo';
//$s2 = 'kepboo';
//echo str_ireplace(str_split($s2), "", $s1);
//$sim = similar_text('umeket', 'ketemu', $perc);
//echo "similarity: $sim ($perc %)\n";    
//$a1=array("a","b","b","c");
//$a2=array("a","c","b");

//$result=array_diff($a1,$a2);
//print_r($result);
//    $word = \App\Words::inRandomOrder()->select('word')->get()->first(); 
    $word = DB::table('words_common')->inRandomOrder()->select('words')->get()->first();
//var_dump($word);die();
    $scrambled_word = str_shuffle($word->words);
//    echo $word['word'];
	$encrypted_word = Crypt::encryptString($word->words);
    return view('welcome', ['links' => $links, 'word' => $scrambled_word, 'original_word' => $word->words , 'encrypted_word' => $encrypted_word ]);
});


Route::get('/scrambler', 'ScrambleController@get_word');

Route::post('/scrambler','ScrambleController@submit_word');

Route::get('/display_score','ScrambleController@display_score');

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
