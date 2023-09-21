@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User's Details</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @elseif ($message = Session::get('success'))
                        <div class="alert alert-success" role = "alert">
                    	<button type="button" class="close" data-dismiss="alert">×</button>        
                            <strong>
                                {{ $message }}
                            </strong>
                        </div>
                        @elseif ($message = Session::get('error'))
                        <div class="alert alert-danger" role = "alert">
                    	<button type="button" class="close" data-dismiss="alert">×</button>>       
                            <strong>
                                {{ $message }}
                            </strong>
                        </div>
                    @endif
                    <div style="font-size:20px">
                        <label>Username:</label>
                         {{ __(Auth::user()->name) }}
                    </div>
                    <div style="font-size:20px">
                        <label>E-mail:</label>
                         {{ __(Auth::user()->email) }}
                    </div>
                    <div style="font-size:20px">
                        <label>PhoneNumber:</label>
                         {{ __(Auth::user()->phonenumber) }}
                    </div>
                    <div style="font-size:20px">
                    <label>Authority:</label>
                        @if (Auth::user()->is_admin)
                             Admin
                        @else
                             User
                        @endif
                    </div>
                </div>
                <form action="{{ route('user.edit', Auth::user()) }}" method='GET'>
                    @csrf
                    <button type="submit" class= 'tablebutton' style="display:inline-block">Edit profile </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
