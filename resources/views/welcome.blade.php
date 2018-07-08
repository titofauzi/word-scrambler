<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">	

        <title>Word Scrambler</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
    	<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

	    .textbox {
		border: none;
		border-bottom-style: solid;
		border-bottom-width: 3px;
		border-color: #f00;
		/*display: block;*/
  		/*margin: 10px;
  		padding: 5px;*/
  		font-size: 60px;
		text-align : left;
	   }
        </style>
    </head>
    <body>
     
	 <nav class="navbar navbar-expand-md navbar-light navbar-laravel shadow-sm">
         
                <a class="navbar-brand h1" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
			     <li class="nav-item">
				<a class="nav-link" href="{{ url('/home') }}">Home</a>
                            </li>
                       
                          
                        @endguest
                    </ul>
                </div>
         
          </nav>
	  <div class="container-fluid">
	  <?php /*
            @if (Route::has('login'))
	    <div class ="row align-items-left">
                <div class="links col-3 my-4">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
	   </div>
            @endif
	   */ ?>
	 
            <div class="row align-items-start">
		<!--
                <div class="title m-b-md hidden">
                    Laravel
                </div>
		-->

		<!--
                <div class="links">
                    @foreach ($links as $link)
                        <a href="{{ $link->url }}">{{ $link->title }}</a>
                    @endforeach
                    
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                
                </div>
		-->
		<div class="col-md-6 align-top">
		    <div id="quiz">	
			<span class="title" id="soal">
			{{ $word }}
			</span>
			<button type="button" class="btn btn-link align-self-center" id="skip-button">Skip</button>
	   <?php /*	<div>{{ $original_word }}</div>
		<div><?php echo Session::getId(); ?></div> */ ?>
			<input type="hidden" value="{{ $encrypted_word }}" id="encrypted_word" />
			<div style="margin-bottom:5px">
				<input type="text" id="text-answer" class="textbox" autofocus />
			</div>
			<div id="countdown"></div>
		    </div>
		    <div id="submit_name" class="invisible">
			<p style="font-size:40px">
				Thank you for playing <span id="your-name"></span>!
				<br /> Your score is : <span id="final-score"></span>
				<button id="play-again" class="btn btn-primary" type="button">Play Again</button>
			</p>
			    <div id="tell-name">
				Please tell us your name :
				<input type="text" id="text-name" class="textbox" />
			   </div>
		    </div>
		</div>
		<div id="display_score" class="col-md-6 mt-4"></div>
            </div>
     </div>
    </body>
<script>

var timerStarted = false;

window.onbeforeunload = function() {a
    if ($('#submit_name').hasClass('invisible') == false) {
	return false;	
    }
    else if ($('#display_score').html() != "") {
	return true;
    }
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#text-answer').keypress(function (e) {
 var key = e.which;
 if(key == 13)  // the enter key code
  {
	if(timerStarted == false){startTimer();}
	$.ajax({
	  method: "POST",
	  url: "scrambler",
	  data: { w : $(this).val() , e : $("#encrypted_word").val() }
	})
	  .done(function( msg ) {
	    obj = jQuery.parseJSON(msg);
	    if(obj.result == '1') { //when correct
		display_score();
		get_new_word();
		
            } else { //when wrong
		display_score();
		}
	   $("#text-answer").val("");
	});
  }
});   

$('#skip-button').click(function(){
	$.ajax({
          method: "POST",
          url: "skip",
          data: { e : $("#encrypted_word").val() }
        })
          .done(function( msg ) {
            //obj = jQuery.parseJSON(msg);
            display_score();
                get_new_word();
           $("#text-answer").val("");
        });	
});

$('#play-again').click(function() {
	location.reload();
});

function display_score(){
	$.ajax({
          method: "GET",
          url: "display_score",
          data: {  }
        })
          .done(function( msg ) { 
             $("#display_score").html(msg);
          });
}

function get_new_word(){
	$.ajax({  
	  method: "GET",
          url: "scrambler",
          data: {  }
        })
          .done(function( msg){
	    $("#soal").html(msg.word);
	    $("#encrypted_word").val(msg.encrypted_word);
           
        });
}

function startTimer() {
timerStarted = true;
// Set the date we're counting down to
let countDownDate = new Date();
countDownDate.setSeconds(countDownDate.getSeconds() + 60);

// Update the count down every 1 second
let x = setInterval(function() {

  // Get todays date and time
  let now = new Date().getTime();

  // Find the distance between now an the count down date
  let distance = countDownDate - now;

  // Display the result in the element with id="demo"
 // document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
 // + minutes + "m " + seconds + "s ";
  document.getElementById("countdown").innerHTML = Math.floor(distance/1000) + "s";
  // If the count down is finished, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
    //$("#quiz").addClass("invisible");
    $("#quiz").text("");
    $("#submit_name").removeClass("invisible");
    show_submit_name();
  }
}, 1000);
}

function show_submit_name(){
	 $.ajax({  
          method: "GET",
          url: "getfinalscore",
          data: {  }
        })
          .done(function( msg ) {           
            $('#final-score').text(msg);
            //$("#soal").html(msg.word);
            //$("#encrypted_word").val(msg.encrypted_word);
        });
}

$('#text-name').keypress(function (e) {
  let key = e.which;
  if(key == 13){

	$.ajax({  
          method: "POST",
          url: "tellname",
          data: { n:($('#text-name').val())  }
        })
          .done(function( msg ) {           
	    $("#your-name").text($("#text-name").val());
	    $("#tell-name").addClass("invisible");
            //$('#final-score').text(msg);
            //$("#soal").html(msg.word);
            //$("#encrypted_word").val(msg.encrypted_word);
        });
  }
});


</script>
</html>
