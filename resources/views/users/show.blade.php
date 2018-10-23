@extends('adminlte::page')
@section('title')
    User Info
@endsection

@section('content')   
    <!-- content wrapper. -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1> Users <small>User Info</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('users.index')}}"><i class="fa fa-user"></i> User</a></li>
                <li class="active">User Details</li>
            </ol>
        </section>
        <!-- end of content header (page header) -->
  
        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">User Info</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group @if ($errors->has('name')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-2">
                                            {{ Form::label('name', 'Name') }}
                                        </div>
                                        <div class="col-xs-12 col-sm-10">
                                            {{ $user->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-2">
                                            {{ Form::label('email', 'Email') }}
                                        </div>
                                        <div class="col-xs-12 col-sm-10">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end of main content-->
    </div>
    <!--end content wrapper. -->
@endsection