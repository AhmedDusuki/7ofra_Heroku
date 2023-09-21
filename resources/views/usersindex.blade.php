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

    <table style='direction:ltr; text-align:left;'>
        <tr>
            <th class="removable" style='direction:ltr; text-align:left;'>ID</th>
            <th style='direction:ltr; text-align:left;'>Name</th>
            <th class="removable" style='direction:ltr; text-align:left;'>E-mail</th>
            <th style='direction:ltr; text-align:left;'>Authority</th>
            <th style='direction:ltr; text-align:left;'></th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td class="removable">{{$user['id']}}</td>
            <td>{{$user['name']}}</td>
            <td class="removable">{{$user['email']}}</td>
            @if($user['is_admin'])
            <td>Admin</td>
            @else
            <td>User</td>
            @endif
           <!-- <td>
                <form action="{{ route('users.update', $user -> id) }}" method="POST" style ="display:none">            
            @csrf
            @method('PUT')
            @if($user['email'] != auth()->user()->email)
                @if($user['is_admin'])
                    <button type="submit" class="buttions">Remove Authority</button>
                @else
                    <button type="submit" class="buttons">Give Authority</button>
                @endif  
            @endif
                </form>
            </td>-->
            <td>
            <form action="{{ route('users.destroy', $user -> id) }}" method="POST">            
            @csrf
            @method('DELETE')
                @if(!$user['is_admin'])
                    <button type="submit" class="buttons">REMOVE</button>
                @endif  
            </form>
            </td>
        </tr>
        @endforeach
    </table>
        </div >
        <script src="js/main.js"></script>
    </body>
</html>