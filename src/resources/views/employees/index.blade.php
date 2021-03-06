@extends('layout')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="users" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Department</th>
                                <th>Access Level</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{$employee->pxt_user_id}}</td>
                                    <td>{{$employee->last_name}}</td>
                                    <td>{{$employee->first_name}}</td>
                                    <td>{{$employee->department}}</td>
                                    <td>{{$employee->access_level}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ URL::to('employees/'.$employee->pxt_user_id) }}" class="btn btn-default btn-sm" onclick="location.href = 'timesheets.staging.dotsoft.gr';">View</a>
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Edit</a></li>
                                                <li><a href="#">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </section>
@stop
