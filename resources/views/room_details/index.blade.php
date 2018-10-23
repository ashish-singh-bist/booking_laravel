@extends('adminlte::page')
@section('title')
    Room Details
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Room<small>Details</small></h1>
        </section>
        <!-- end of content header (page header) -->
        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Filters</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
                                <a id='clear_filter' class="btn btn-danger">Clear Filters</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">CheckIn Date</h4>
                                        </div>
                                        <div class="box-body overflow-0">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="checkin_date_from">From</label>
                                                    <p class="input-group date" data-provide="datepicker">
                                                        <input type="text" class="form-control filter_class" id="checkin_date_from" readonly="readonly">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="checkin_date_to">To</label>
                                                    <p class="input-group date" data-provide="datepicker">
                                                        <input type="text" class="form-control filter_class" id="checkin_date_to" readonly="readonly">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Parse Date</h4>
                                        </div>
                                        <div class="box-body overflow-0">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="created_at_from">From</label>
                                                    <p class="input-group date" data-provide="datepicker">
                                                        <input type="text" class="form-control filter_class" id="created_at_from" readonly="readonly">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="created_at_to">To</label>
                                                    <p class="input-group date" data-provide="datepicker">
                                                        <input type="text" class="form-control filter_class" id="created_at_to" readonly="readonly">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Room Type</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="room_types" multiple="multiple">
                                                @foreach($room_type_list as $room_type)
                                                    <option value="{{$room_type}}">{{$room_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 text-right">
                                    <a id='filter_apply' class="btn btn-success">Apply Filters</a>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered" id="room_details">
                                <thead><tr>
                                    @foreach (config('app.room_details_header_key') as $value) <th>{{$value}}</th> @endforeach
                                </tr></thead>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#datepicker').datepicker();
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $.fn.dataTable.ext.errMode = 'none';
            var oTable = $('#room_details').DataTable({
                "aLengthMenu": [5, 10, 25, 50, 100, 500, 1000],
                "iDisplayLength": 100,
                "sPaginationType" : "full_numbers",
                processing: true,
                serverSide: true,
                searching: false,
                select: {
                    style: 'multi'
                },
                dom: "<'row'<'col-sm-2'li><'col-sm-10'B><'col-sm-10'p>>rt<'bottom'ip><'clear'>",
                buttons: [{
                          text: 'Export CSV',
                          action: function (e, dt, node, config)
                          {
                            $.ajax({
                                @if(isset($id))
                                    "url": "{!! route('room_details.index.getData') !!}?id={{$id}}?export=csv",
                                @else
                                    "url": "{!! route('room_details.index.getData') !!}?export=csv",
                                @endif
                                "data": dt.ajax.params(),
                                "success": function(res, status, xhr) {
                                    var csvData = new Blob([res], {type: 'text/csv;charset=utf-8;'});
                                    var csvURL = window.URL.createObjectURL(csvData);
                                    var tempLink = document.createElement('a');
                                    tempLink.href = csvURL;
                                    tempLink.setAttribute('download', 'data.csv');
                                    tempLink.click();
                                }
                            });
                          }
                        }],
                ajax: {
                    @if(isset($id))
                        url: "{!! route('room_details.index.getData') !!}?id={{$id}}",
                    @else
                        url: "{!! route('room_details.index.getData') !!}",
                    @endif                    
                    data: function (d) {
                        d.room_types = $("#room_types").val();
                        d.created_at_to = $('#created_at_to').val();
                        d.created_at_from = $('#created_at_from').val();
                        // d.checkin_date_to = $('#checkin_date_to').val();
                        // d.checkin_date_from = $('#checkin_date_from').val();
                    }
                },                
                columns: [
                    @foreach (config('app.room_details_header_key') as $key => $value) { data: '{{$key}}', name: '{{$key}}' }, @endforeach
                ]
            });

            $('#filter_apply').on('click', function(e) {
                oTable.draw();
            });

            $('#clear_filter').on('click',function(){
                $("#room_types").val('').trigger('change');
                // $("#checkin_date_to").val('');
                // $("#checkin_date_from").val('');
                $("#created_at_to").val('');
                $("#created_at_from").val('');
                oTable.draw();
            });

            $('#room_types').select2({
                placeholder: 'Select room type',
                allowClear: true,
                //minimumResultsForSearch: 5,
            }); 
        });
    </script>
@endsection