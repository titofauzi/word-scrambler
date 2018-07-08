<?php

namespace App\Http\Controllers;

use App\User;
use App\Words;
use App\Score;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ScrambleController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    private $_reward_modifier = 2;
    private $_punishment_modifier = -1;

    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    public function get_word(){
	$word = DB::table('words_common')->inRandomOrder()->select('words')->get()->first();
	
	$scrambled_word = str_shuffle($word->words);
 	$encrypted_word = Crypt::encryptString($word->words);
    	$return = ['word' => $scrambled_word, 'original_word' => $word->words , 'encrypted_word' => $encrypted_word ];
	return response()->json($return);	
	
    }

     public function submit_word(Request $request){
	//var_dump($word);die();
	$decrypted_word = Crypt::decryptString($request->e);
	if($request->w == $decrypted_word){

		$score = new Score;
		$score->word = $decrypted_word;
		$score->answer = $request->w;
		$score->score = strlen($request->w) * $this->_reward_modifier;
		$score->session_id = session()->getId();
		$score->save();
		
		echo json_encode(['result'=>1 ]);
	}
	else {
		$score = new Score;
                $score->word = $decrypted_word;
                $score->answer = $request->w;
                $score->score = strlen($request->w) * $this->_punishment_modifier;
		$score->session_id = session()->getId();
                $score->save();
		
		echo json_encode(['result'=>0]);
	}
    }

    public function skip(Request $request){
	$decrypted_word = Crypt::decryptString($request->e);
	$score = new Score;
        $score->word = $decrypted_word;
        $score->answer = 'SKIP';
        $score->score = -2;
        $score->session_id = session()->getId();
        $score->save();
    }

    public function getfinalscore(Request $request){
        $scores = DB::table('scores')->where('session_id', '=', session()->getId())
					->sum('score')
					;
    	return response()->json($scores);
    }

    public function display_score(){

	$return = '<table class="table table-sm">';
	$return .= '<tr><th>Answer</th><th>Score</th></tr>';
	$scores = DB::table('scores')->where('session_id', '=', session()->getId())->orderBy('created_at', 'desc')->get();
	$sum = 0;
	foreach($scores as $score) {
		
		if($score->answer == 'SKIP'){
			$class= "text-info";
		}
		else if ($score->score < 0 ){
			$class = "text-danger";
		}
		else {
			$class = "";		
		}
		
		$return .= '<tr class="'.$class.'"><td>'.$score->answer.'</td><td>'.$score->score.'</td></tr>';
		$sum = $sum + $score->score;	
	}
	$return .= '<tr><td></td><td id="table-final-score" class="font-weight-bold">'.$sum.'</td><tr>';
	$return .= '</table>';
	
	echo $return;
    }
	
    public function tellname(Request $request){
	DB::table('scores')
            ->where('session_id', session()->getId())
            ->update(['name' => $request->n]);
    }
}
