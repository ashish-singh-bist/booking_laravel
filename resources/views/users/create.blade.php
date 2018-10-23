@extends('adminlte::page')
@section('title')
    Create User
@endsection

@section('content') 
    <!-- content wrapper. contains page content -->
    <div class="content-panel"> 
        <!-- content header (page header) -->
        <section class="content-header">
            <h1> Users <small>Create User</small> </h1>
            <ol class="breadcrumb">
                {{-- <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li> --}}
                <li><a href="{{route('users.index')}}"><i class="fa fa-user"></i> User</a></li>
                <li class="active">Create</li>
            </ol>
        </section>
        <!--end of content header (page header) -->

        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Create User</h3>
                            </div>
                            <div class="box-body">
                                {!! Form::open(array('url' => 'users', 'class' => 'create_user') ) !!}
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::label('name', 'Name') !!}
                                            {!! Form::text('name', '', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::label('email', 'Email') !!}
                                            {!! Form::email('email', '', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::label('password', 'Password') !!}<br>
                                            {!! Form::password('password', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::label('password', 'Confirm Password') !!}<br>
                                            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('user_type')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-8">
                                            <label for="user_type">Role</label>
                                            <select name="user_type" class="form-control" id="id_user_type">
                                                    <option id="admin" selected="selected">Admin</option>
                                                    <option id="super_admin">Super Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Register', array('class' => 'btn btn-primary')) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end of main content-->
    </div>
    <!-- end of content wrapper. contains page content -->
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
@endsection