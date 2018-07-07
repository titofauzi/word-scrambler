<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	$session_id = DB::table('scores')->select('session_id')->distinct()->get();
	foreach ($session_id as $ind_session_id) {
		$scores = DB::table('scores')->where('session_id', '=', $ind_session_id->session_id)->orderBy('created_at', 'desc')->get();
		$array_1 = []; //re-initialize array
		foreach($scores as $score) {
			$array_1[] = ["word" => $score->word, 
					"answer" => $score->answer, 
					"score" => $score->score];
		}
			$array[]= array_add(['ind_session_id' => $ind_session_id->session_id], 
					    'scores', $array_1);
		
	}
        return view('home', ['session_id' => $array]);
    }
}
