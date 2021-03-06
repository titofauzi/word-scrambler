@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-none">Dashboard</div>

                <div class="card-body d-none">
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
		    <div class="row">
		    @foreach ($session_id as $ind_session_id)
			<div class="col">
			<div class="card-header">
			
				@if ($ind_session_id['name'] != "")  [<strong>{{ $ind_session_id['name'] }}</strong>] 
				@else [<strong>guest</strong>]
				@endif
				{{ $ind_session_id['created_at'] }}
			</div>
			<table class="table">
			<tr><th>Word</th><th>Answer</th><th>Score</th></tr>
			 @foreach ($ind_session_id['scores'] as $score)
			<tr><td>{{ $score['word'] }}</td><td>{{ $score['answer']  }}</td><td>{{ $score['score'] }}</td></tr>	
			
			 @endforeach
			<tr><td></td><td></td><td>{{ $ind_session_id['sum'] }}</td></tr>
			</table>
			</div>
                    @endforeach
		    </div>
		</div>
            </div>
        </div>
    </div>
</div>
@endsection
