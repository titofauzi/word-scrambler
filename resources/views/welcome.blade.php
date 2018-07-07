<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">	

        <title>Laravel</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
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
		text-align : center;
	   }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
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
		<div class="title m-b-md" id="soal">
			{{ $word }}
		</div>
		<div>{{ $original_word }}</div>
		<div><?php echo Session::getId(); ?></div>
		<input type="hidden" value="{{ $encrypted_word }}" id="encrypted_word" />
		<div style="margin-bottom:5px"><input type="text" id="text-answer" class="textbox" /></div>
		<div id="display_score"></div>
            </div>
        </div>
    </body>
<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#text-answer').keypress(function (e) {
//console.log('dad');
 var key = e.which;
 if(key == 13)  // the enter key code
  {
//    $('input[name = butAssignProd]').click();
  //  return false;  
	$.ajax({
	  method: "POST",
	  url: "scrambler",
	  data: { w : $(this).val() , e : $("#encrypted_word").val() }
	})
	  .done(function( msg ) {
	    obj = jQuery.parseJSON(msg);
		//console.log(obj);
	    if(obj.result == '1') { 
		console.log(obj.result);
		display_score();
		get_new_word();
            } else {
		display_score();
		}
	    //alert( "Data Saved: " + msg );
	});

	
  }
});   

function display_score(){
	$.ajax({
          method: "GET",
          url: "display_score",
          data: {  }
        })
          .done(function( msg ) {
                //alert(msg);
             $("#display_score").html(msg);
            //alert( "Data Saved: " + msg );
          });

}

function get_new_word(){
	$.ajax({  
	  method: "GET",
          url: "scrambler",
          data: {  }
        })
          .done(function( msg ) {
	   //caonsole.log(msg);
	    //obj = jQuery.parseJSON(msg);
	 //  console.log(obj);
	    $("#soal").html(msg.word);
	    $("#encrypted_word").val(msg.encrypted_word);
            //alert( "Data Saved: " + msg );
        });
}

</script>
</html>
