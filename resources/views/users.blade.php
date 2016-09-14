@extends('layout')

@section('content')

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <th scope="row">{{$user->id}}</th>
            <td>{{$user->last_name}}</td>
            <td>{{$user->first_name}}</td>
            <td>{{$user->department}}</td>
            <td>{{$user->access_level}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

@stop
