@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body hidden">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
		
		<div class="card-header">Scores</div>
		<div class="card-body">
		<pre>
		<?php //print_r($session_id);die(); ?>
		</pre>
		    @foreach ($session_id as $ind_session_id)
			<div class="card-header">
				{{ $ind_session_id['ind_session_id'] }}
			</div>
			<table class="table">
			<tr><th>Word</th><th>Answer</th><th>Score</th></tr>
			 @foreach ($ind_session_id['scores'] as $score)
			<tr><td>{{ $score['word'] }}</td><td>{{ $score['answer']  }}</td><td>{{ $score['score'] }}</td></tr>	
			 @endforeach
			</table>
                    @endforeach
		</div>
            </div>
        </div>
    </div>
</div>
@endsection
