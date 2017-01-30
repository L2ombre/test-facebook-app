@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>

                <div class="panel-body">
                    Hi {{ $loggedInUser->name }}, you are logged in!
                    <br />
                    <br />
                    Here is your avatar:
                    <br />
                    <br />
                    <img src="{{ $loggedInUser->avatar }}" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
