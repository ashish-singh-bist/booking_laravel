@extends('adminlte::page')
@section('title')
    All Users 
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Users
                <small>All Users</small>
                @if(auth()->user()->user_type =='super_admin')
                <a class="btn btn-primary" href="{{ route('users.create') }}">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    Create User
                </a>
                @endif
            </h1>
        </section>
        <!-- end of content header (page header) -->

        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end of main content-->
    </div>
    <!-- end of content wrapper. contains page content -->
@endsection
@section('js')
    <script type="text/javascript">
        $(function() {
            $('#users-table').DataTable({
                "aLengthMenu": [10,25, 50, 100, 500, 1000],
                "iDisplayLength": 25,
                "sPaginationType" : "full_numbers",
                processing: true,
                serverSide: true,
                select: {
                    style: 'multi'
                },
                ajax: "{!! route('users.index.getdata') !!}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $('#users-table').on('click', '.btn-delete[data-remote]', function (e) { 
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var url = $(this).data('remote');
                // confirm then
                $.confirm({
                    title: 'Confirm!',
                    content: 'Do you want to Remove this user ?',
                    buttons: {
                        confirm: {                  
                            text: 'Confirm',
                            btnClass: 'btn-red',
                            keys: ['enter'],
                            action: function(){
                                $.ajax({
                                    url: url ,
                                    type: 'DELETE',
                                    dataType: 'json',
                                    data: {method: '_DELETE', submit: true}
                                }).always(function (data) {
                                    $('#users-table').DataTable().draw(false);
                                    if(data.status){
                                        $('#error_block').html('<div class="alert alert-success alert-important" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+ data.message +'</div>');
                                    }
                                    else{
                                        $('#error_block').html('<div class="alert alert-danger alert-important" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+ data.message +'</div>');
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-gray',
                            keys: ['enter'],
                            action: function(){
                                console.log("cancle");
                            }
                        }
                    }
                });
                // end of confirm then
            });
        });
    </script>
@endsection