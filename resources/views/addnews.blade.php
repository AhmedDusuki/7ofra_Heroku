<!DOCTYPE html>
 
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
        <title>DashBoard</title>
        <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cairo&family=Inconsolata:wght@900&display=swap" rel="stylesheet">
    </head>
    
    <body>
        
        <header style="position: static;">
            <p>7ofra</p>
            <a class="closebtn" id="clop">&times;</a>
            <div id="myNav" class="overlay">
            <nav>
            <a href="{{  route('home')  }}"> Back</a>  
            <a href="{{ route('profile' , Auth::user()->name ) }}">{{  (Auth::user()->name)   }}</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> logout</a> 
            </nav>
            </div>
        </header>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        </form>
       
        <div style="height: 100%">

<div class="newsform">
    <form action="{{ route('fires.store') }}" method="POST" class="newsform">
        @csrf
        <div class ="newsform">
            <label class="newsform">العنوان:</label>
            <input type="text" name="subject" placeholder="العنوان" required>
        </div>
        <div class="newsform">
            <label class="newsform">الموضوع:</label>
            <textarea name="description" placeholder="الموضوع" required></textarea>
        </div>
        <div class= "newsform">
            <button type="submit" class="newsform">SUBMIT</button>
        </div>
    </form>
</div> 
<script src="../js/main.js"></script>
    </body>
</html>