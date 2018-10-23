@extends('adminlte::page')
@section('title')
    Hotel Prices
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Hotel<small>Prices</small></h1>
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
                        <div class="box-body hotel-price">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Days</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="days[]" value="1"/> 1 day</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="days[]" value="2"/> 2 day</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="days[]" value="3"/> 3 day</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="days[]" value="5"/> 5 day</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="days[]" value="7"/> 7 day</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Max Person</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="max_persons[]" value="1"/> 1 person</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="max_persons[]" value="2"/> 2 person</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="max_persons[]" value="3"/> 3 person</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="max_persons[]" value="4"/> 4 person</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="max_persons[]" value="5"/> 5 person</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Star</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="1"/> 1 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="2"/> 2 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="3"/> 3 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="4"/> 4 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="5"/> 5 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="6"/> 6 stars</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="7"/> 7 stars</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Rating</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="0"/> 0 - 1</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="1"/> 1 - 2</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="2"/> 2 - 3</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="3"/> 3 - 4</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="4"/> 4 - 5</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="5"/> 5 - 6</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="6"/> 6 - 7</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="7"/> 7 - 8</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="8"/> 8 - 9</li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="9"/> 9 - 10</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                        <div class="box-header">
                                            <h4 class="box-title">Available Only</h4>
                                        </div>
                                        <div class="box-body">
                                            <ul>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="1"/> 1 room</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="2"/> 2 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="3"/> 3 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="4"/> 4 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="5"/> 5 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="6"/> 6 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="7"/> 7 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="8"/> 8 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="9"/> 9 rooms</li>
                                                <li><label><input class="flat-icheck" type="checkbox" name="available_only[]" value="10"/> 10 rooms</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                        <div class="box-header">
                                            <h4 class="box-title">Category</h4>
                                        </div>
                                        <div class="box-body">
                                            <ul>
                                            @foreach($category_list as $category)
                                                <li><label><input class="flat-icheck"  type="checkbox" name="category[]" value="{{$category[0]}}"/> {{$category[0]}}</label></li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>                                                            
                            </div>
                            <div class="row">
                                <div class="col-md-6">
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
                                </div>
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
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Room Type</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="room_types" multiple="multiple">
                                                @foreach($room_type_list as $room_type)
                                                    <option value="{{$room_type[0]}}">{{$room_type[0]}}</option>
                                                @endforeach                                     
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Price Range</h4>
                                        </div>
                                        <div class="box-body">
                                            <div id="slider-range"></div>
                                                <input class="slider-range-data" type="text" id="amount" readonly>
                                                <input class="slider-range-data" type="hidden" id="min_price" readonly>
                                                <input class="slider-range-data" type="hidden" id="max_price" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Meal Plan</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="meal_plan">
                                                <option value="">Any</option>
                                                <option value="empty">Empty</option>
                                                <option value="not-empty">Not Empty</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Cancellation Type</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="cancellation_type" multiple="multiple">
                                                @foreach($cancel_type_list as $cancel_type)
                                                    <option value="{{$cancel_type[0]}}">{{$cancel_type[0]}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Other Desc</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="others_desc" multiple="multiple">
                                            @foreach($other_desc_list as $other_desc)
                                                @if ($other_desc[0])
                                                    <option value="{{$other_desc[0]}}">{{$other_desc[0]}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Country</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class select2-country" id="countries" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">City</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class select2-city" id="cities" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Hotel Name</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class select2-hotel_names" id="hotel_names" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Guest Available</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="guest_available">
                                                <option value="">Any</option>
                                                <option value="empty">Empty</option>
                                                <option value="not-empty">Not Empty</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Self Verified</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="self_verified">
                                                <option value="">Any</option>
                                                <option value="1">Verified</option>
                                                <option value="0">Not Verified</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="box box-primary box-solid filter-box">
                                        <div class="box-header">
                                            <h4 class="box-title">Guest Favourite Area</h4>
                                        </div>
                                        <div class="box-body">
                                            <select class="form-control filter_class" id="guest_favourite">
                                                <option value="">Any</option>
                                                <option value="1">Favourite</option>
                                                <option value="0">Not Favourite</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 text-right">
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
                            <table class="table table-bordered" id="hotel_prices">
                                <thead><tr>
                                    @foreach (config('app.hotel_prices_header_key') as $key => $value)
                                        @if($key == 'raw_price')
                                            <th>{{$value}}(&euro;)</th>
                                        @else
                                            <th>{{$value}}</th>
                                        @endif
                                    @endforeach
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
    <!-- Modal -->
    <div id="equip_model" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
{{--     <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script> --}}
    <script type="text/javascript">
        $(function () {
            $('#datepicker').datepicker();
        });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            $.fn.dataTable.ext.errMode = 'none';
            var oTable = $('#hotel_prices').DataTable({
                "aLengthMenu": [5, 10, 25, 50, 100, 500, 1000],
                "iDisplayLength": 100,
                "sPaginationType" : "full_numbers",
                processing: true,
                serverSide: true,
                searching: false,
                select: {
                    style: 'multi'
                },
                scrollY:        "300px",
                scrollX:        true,
                scrollCollapse: true,
                // fixedColumns:   {
                //     leftColumns: 1
                // },
                deferLoading: false,
                dom: "<'row'<'col-sm-2'li><'col-sm-4'B><'col-sm-6'<'#statistics.text-right'>p>>rt<'bottom'ip><'clear'>",
                buttons: [{
                          text: 'Export CSV',
                          action: function (e, dt, node, config)
                          {
                            $.ajax({
                                @if(isset($id))
                                    "url": "{!! route('hotel_prices.index.getData') !!}?id={{$id}}?export=csv",
                                @else
                                    "url": "{!! route('hotel_prices.index.getData') !!}?export=csv",
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
                        url: "{!! route('hotel_prices.index.getData') !!}?id={{$id}}",
                    @else
                        url: "{!! route('hotel_prices.index.getData') !!}",
                    @endif
                    data: function (d) {
                        d.room_types = $("#room_types").val();
                        d.created_at_to = $('#created_at_to').val();
                        d.created_at_from = $('#created_at_from').val();
                        d.min_price = $('#min_price').val();
                        d.max_price = $('#max_price').val();
                        d.checkin_date_to = $('#checkin_date_to').val();
                        d.checkin_date_from = $('#checkin_date_from').val();
                        d.meal_plan = $('#meal_plan').val();
                        d.cancellation_type = $('#cancellation_type').val();
                        d.others_desc = $('#others_desc').val();
                        var stars = $('input[name="stars[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        var ratings = $('input[name="ratings[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        d.stars = stars;
                        d.ratings = ratings;
                        var days = $('input[name="days[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        var max_persons = $('input[name="max_persons[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        var categories = $('input[name="category[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        var available_only = $('input[name="available_only[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        d.guest_available = $('#guest_available').val();
                        d.days = days;
                        d.max_persons = max_persons;
                        d.cities = $("#cities").val();
                        d.countries = $("#countries").val();
                        d.hotel_names = $("#hotel_names").val();
                        d.categories = categories;
                        d.available_only = available_only;
                        d.self_verified = $("#self_verified").val();
                        d.guest_favourite = $("#guest_favourite").val();
                    },
                    dataFilter: function(response) {
                        var statistics = JSON.parse(response)['statistics'];
                        var room_array = JSON.parse(response)['room_array'];
                        var room_types = $("#room_types").val();
                        $("#room_types").empty().select2( { data : room_array, placeholder: 'Select room type' });
                        $('#room_types').val(room_types).trigger('change');
                        $("#statistics").html('<div><span class="p_badge"><b>Max Price : </b>&euro;'+statistics['max_price'].toFixed(2)+'</span>|<span class="p_badge"><b>Min Price : </b>&euro;'+statistics['min_price'].toFixed(2)+'</span>|<span class="p_badge"><b>Avg. Price : </b>&euro;'+statistics['avg_price'].toFixed(2)+'</span></div>');
                        return response;
                    },
                },
                columns: [
                    @foreach (config('app.hotel_prices_header_key') as $key => $value) 
                        { data: '{{$key}}', name: '{{$key}}' },
                    @endforeach
                ],
                columnDefs: [
                    { "orderable": false, "targets": [] },
                    { "orderable": true, "targets": [0, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18] },
                    { "width": "120px", "targets": 0 },
                    { "width": "60px", "targets": 1 },
                    { "width": "30px", "targets": 2 },
                    { "width": "100px", "targets": 3 },
                    { "width": "40px", "targets": 6 },
                    { "width": "40px", "targets": 7 },
                    { "width": "40px", "targets": 8 },
                    { "width": "40px", "targets": 9 },
                    { "width": "30px", "targets": 10 },
                    { "width": "30px", "targets": 11 },
                    { "width": "100px", "targets": 12 },
                    { "width": "50px", "targets": 13 },
                    { "width": "100px", "targets": 14 },
                    { "width": "100px", "targets": 15 }
                ],
                "order": [[ 7, "desc" ]]
            });

            $('#filter_apply').on('click', function(e) {
                oTable.draw();
            });

            $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 0, 500 ],
                change: function( event, ui ) { },
                slide: function( event, ui ) {
                    $( "#min_price" ).val( ui.values[ 0 ] );
                    if(ui.values[ 1 ] >= 500){
                        $( "#max_price" ).val( '' );
                        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] + "+" );
                    }else{
                        $( "#max_price" ).val( ui.values[ 1 ] );
                        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                    }
                }
            });
            setTimeout(function(){ $( "#amount" ).val("$0" + " - $500+"); }, 1000);

            $('#clear_filter').on('click',function(){
                $("#checkin_date_to").val('');
                $("#checkin_date_from").val('');
                $("#room_types").val('').trigger('change');
                $("#created_at_to").val('');
                $("#created_at_from").val('');
                $("#meal_plan").val('').trigger('change');
                $('#guest_available').val('').trigger('change');
                $("#cancellation_type").val('').trigger('change');
                $("#others_desc").val('').trigger('change');
                $(".select2-country").val('').trigger('change');
                $(".select2-city").val('').trigger('change');
                $(".select2-hotel_names").val('').trigger('change');
                $('.flat-icheck').iCheck('uncheck');
                $('#self_verified').val('').trigger('change');
                $('#guest_favourite').val('').trigger('change');
                resetSlider();
                oTable.draw();
            });

            function resetSlider() {
                var $slider = $("#slider-range");
                var min_value = 0;
                var max_value = 500;
                $slider.slider("values", 0, min_value);
                $slider.slider("values", 1, max_value);
                $( "#min_price" ).val(0);
                $( "#max_price" ).val(500);
                $( "#amount" ).val( "$" + min_value + " - $" + max_value + "+" );
            }

            $('#room_types').select2({
                placeholder: 'Select room type',
                allowClear: true,
            });

            $('#cancellation_type').select2({
                placeholder: 'Select cancellation type',
                allowClear: true,
            });

            $('#others_desc').select2({
                placeholder: 'Select other desc',
                allowClear: true,
            });

            $('#hotel_names').select2({
                placeholder: 'Select a hotel',
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '{{route("get_filter_list")}}',
                    dataType: 'json',
                    data: function (params) {
                      var query = {
                        search: params.term,
                        type: 'HotelName'
                      }
                      return query;
                    },
                    processResults: function (data) {
                        return {
                          results: data
                        };
                    },
                    cache: true
                }
            });

            $('#countries').select2({
                placeholder: 'Select a country',
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '{{route("get_filter_list")}}',
                    dataType: 'json',
                    data: function (params) {
                      var query = {
                        search: params.term,
                        type: 'Country'
                      }
                      return query;
                    },
                    processResults: function (data) {
                        return {
                          results: data
                        };
                    },
                    cache: true
                }
            });

            $('#cities').select2({
                placeholder: 'Select a city',
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '{{route("get_filter_list")}}',
                    dataType: 'json',
                    data: function (params) {
                      var query = {
                        search: params.term,
                        type: 'City'
                      }
                      return query;
                    },
                    processResults: function (data) {
                        return {
                          results: data
                        };
                    },
                    cache: true
                }
            });

            $('#hotel_prices tbody').on('click', '.hotel_equip_popup', function() {
                var data_title = $(this).attr("data-title");
                var hotel_id = $(this).attr("hotel-id");
                var data = {'hotel_id':hotel_id};
                $.ajax({
                    type: "POST",
                    url: '{{route("getHotelEquipment")}}',
                    dataType: "json",
                    data: data,
                    success:function(data){
                        if(typeof(data.data) == 'string'){
                            var equipment_data = JSON.parse(data.data.replace(/'/g, "\""));
                        }else{
                            var equipment_data = data.data
                        }
                        var html = '';
                        $.each(equipment_data, function(key, value){
                            html += '<div class="col-sm-12"><h5>' + key + '</h5></div>';
                            html_inner = '<div class="col-sm-12">';
                            $.each(value, function(key, value){
                                html_inner += '<div class="equip_badge">' + key + '</div>';
                            });
                            if(html_inner != '<div class="col-sm-12">'){
                                html += html_inner + '</div>';
                            }
                        });
                        if(html == ''){
                            html = '<div class="col-sm-12">No data found.</div>';
                        }                        
                        $('#equip_model .modal-body').html(html);
                        $('#equip_model h4').html('<b>Hotel Name:-</b> ' + data_title);
                        $('#equip_model').modal('toggle');
                    },
                    error: function(error) {
                        $('#equip_model .modal-body').html('Something Went Wrong');
                        $('#equip_model h4').html('Warning');
                        $('#equip_model').modal('toggle');
                    }
                });
            });

            $('#hotel_prices tbody').on('click', '.room_equip_popup', function() {
                var room_type = $(this).attr("data-title");
                var hotel_id = $(this).attr("hotel-id");
                var data = {'room_type':room_type};
                $.ajax({
                    type: "POST",
                    url: '{{route("getRoomEquipment")}}',
                    dataType: "json",
                    data: data,
                    success:function(data){
                        if(typeof(data.data) == 'string'){
                            var equipment_data = JSON.parse(data.data.replace(/'/g, "\""));
                        }else{
                            var equipment_data = data.data
                        }
                        var html = '<div class="col-sm-12">';
                        $.each(equipment_data, function(key, value){
                            html += '<div class="equip_badge">' + key + '</div>';
                        });
                        html += '</div>';
                        if(html == '<div class="col-sm-12"></div>'){
                            html = '<div class="col-sm-12">No data found.</div>';
                        }
                        $('#equip_model .modal-body').html(html);
                        $('#equip_model h4').html('<b>Room Type:-</b> ' + room_type);
                        $('#equip_model').modal('toggle');
                    },
                    error: function(error) {
                        $('#equip_model .modal-body').html('Something Went Wrong');
                        $('#equip_model h4').html('Warning');
                        $('#equip_model').modal('toggle');
                    }
                });
            });
        });
    </script> 
@endsection