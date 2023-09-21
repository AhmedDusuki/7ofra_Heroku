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
    <div>
    <table>
        <tr>
            <th>العنوان</th>
            <th>الموضوع</th>
            <th></th>    
        </tr>
        @foreach($fires as $new)
        <tr>
            
            <td><strong>{{$new['subject']}}</strong></td>
            <td>{{$new['description']}}</td>
            <td>
                <form action="{{ route('fires.destroy',$new->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="buttons">DELETE</button>
                </form>
            </td>
        </tr>
        @endforeach
        </table>
        <form action="{{ route('fires.create') }}" method="GET">
            @csrf
        <button  class="tablebutton" type="submit" >ADD</button>
        </form>
        </div >
</div>
<script src="js/main.js"></script>
    </body>
</html>