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
	$session_id = DB::table('scores')->select(DB::raw("DISTINCT ON (session_id, name) session_id, name, created_at"))->get();
	$session_id = DB::select('SELECT * FROM (SELECT DISTINCT ON (session_id, name) session_id, name, created_at FROM scores) sub order by 3 desc');
	foreach ($session_id as $ind_session_id) {
		$scores = DB::table('scores')
			  ->where('session_id', '=', $ind_session_id->session_id)
			  ->orderBy('created_at', 'desc')
			  ->get();
		$array_1 = []; //re-initialize array
		$sum = 0;
		foreach($scores as $score) {
			$array_1[] = ["word" => $score->word, 
					"answer" => $score->answer, 
					"score" => $score->score];
			$sum = $sum + $score->score;
		}
			$array_2= array_add(['ind_session_id' => $ind_session_id->session_id,
					     'name' => $ind_session_id->name,
					     'created_at' => $ind_session_id->created_at],
					     'scores', $array_1
						);
			$array[]= array_add($array_2,'sum',$sum);
		
	}
        return view('home', ['session_id' => $array]);
    }
}
