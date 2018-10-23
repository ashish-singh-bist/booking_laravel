@extends('adminlte::page')
@section('title')
    Hotel Details
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Hotel<small>Details</small></h1>
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
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Category</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                    @foreach($category_list as $category)
                                                        <li><label><input class="flat-icheck"  type="checkbox" name="category[]" value="{{$category}}"/> {{$category}}</label></li>
                                                    @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Star</h4>
                                                </div>
                                                <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="1"/> 1 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="2"/> 2 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="3"/> 3 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="4"/> 4 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="5"/> 5 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="6"/> 6 stars</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="stars[]" value="7"/> 7 stars</label></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="box box-primary box-solid filter-box filter-box-fix-height">
                                                <div class="box-header">
                                                    <h4 class="box-title">Rating</h4>
                                                </div>
                                                <div class="box-body">
                                                	<label for="min_rating">From (min)</label><br>
                                                	<input type="number" id="min_rating" placeholder="min" min="0" max="10" step=".1"><br>
                                                	<label for="max_rating">To (max)</label><br>
                                                	<input type="number" id="max_rating" placeholder="max" min="0" max="10" step=".1">
                                                </div>
                                                {{-- <div class="box-body">
                                                    <ul>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="0"/> 0 - 1</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="1"/> 1 - 2</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="2"/> 2 - 3</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="3"/> 3 - 4</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="4"/> 4 - 5</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="5"/> 5 - 6</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="6"/> 6 - 7</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="7"/> 7 - 8</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="8"/> 8 - 9</label></li>
                                                        <li><label><input class="flat-icheck" type="checkbox" name="ratings[]" value="9"/> 9 - 10</label></li>
                                                    </ul>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="box box-primary box-solid filter-box">
                                                <div class="box-header">
                                                    <h4 class="box-title">Country</h4>
                                                </div>
                                                <div class="box-body">
                                                    <select class="form-control filter_class select2-country" id="countries" multiple="multiple"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="box box-primary box-solid filter-box">
                                                <div class="box-header">
                                                    <h4 class="box-title">City</h4>
                                                </div>
                                                <div class="box-body">
                                                    <select class="form-control filter_class select2-city" id="cities" multiple="multiple"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
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
                            <table class="table table-bordered" id="hotel_master">
                                <thead><tr>
                                    @foreach (config('app.hotel_master_header_key') as $value) <th>{{$value}}</th> @endforeach
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
            var oTable = $('#hotel_master').DataTable({
                "aLengthMenu": [5, 10, 25, 50, 100, 500, 1000],
                "iDisplayLength": 100,
                "sPaginationType" : "full_numbers",
                processing: true,
                serverSide: true,
                searching: false,
                select: {
                    style: 'multi'
                },
                dom: "<'row'<'col-sm-2'li><'col-sm-4'B><'col-sm-6'<'#statistics.text-right'>p>>rt<'bottom'ip><'clear'>",
                buttons: [{
                          text: 'Export CSV',
                          action: function (e, dt, node, config)
                          {
                            $.ajax({
                                @if(isset($id))
                                    "url": "{!! route('hotel_master.getData') !!}?id={{$id}}?export=csv",
                                @else
                                    "url": "{!! route('hotel_master.getData') !!}?export=csv",
                                @endif
                                "data": dt.ajax.params(),
                                "success": function(res, status, xhr) {
                                    var csvData = new Blob([res], {type: 'text/csv;charset=utf-8;'});
                                    if (navigator.appVersion.toString().indexOf('.NET') > 0){
                                        window.navigator.msSaveBlob(csvData, 'data.csv');
                                    }
                                    else{
                                        var csvURL = window.URL.createObjectURL(csvData);
                                        var tempLink = document.createElement('a');
                                        tempLink.href = csvURL;
                                        tempLink.setAttribute('download', 'data.csv');
                                        tempLink.click();
                                    }
                                }
                            });
                          }
                        }],
                ajax: {
                    @if(isset($id))
                        url: "{!! route('hotel_master.getData') !!}?id={{$id}}",
                    @else
                        url: "{!! route('hotel_master.getData') !!}",
                    @endif
                    data: function (d) {
                        d.created_at_to = $('#created_at_to').val();
                        d.created_at_from = $('#created_at_from').val();
                        var stars = $('input[name="stars[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        // var ratings = $('input[name="ratings[]"]:checked')
                        // .map(function() {
                        //     return $(this).val();
                        // }).get();
                        var categories = $('input[name="category[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                        d.stars = stars;
                        // d.ratings = ratings;
                        d.min_rating = $('#min_rating').val();
                        d.max_rating = $('#max_rating').val();
                        d.categories = categories;
                        d.cities = $("#cities").val();
                        d.countries = $("#countries").val();
                        d.self_verified = $("#self_verified").val();
                        d.guest_favourite = $("#guest_favourite").val();
                    },
                    dataFilter: function(response) {
                        var data_json = JSON.parse(response);
                        if( data_json['data'].length == 0){
                            $.alert({
                                title: 'Alert!',
                                content: 'No Data Found!',
                            });
                        }
                        var statistics = JSON.parse(response)['statistics'];
                        $("#statistics").html('<div><span class="p_badge"><b>Max Rating : </b>'+statistics['max_rating'].toFixed(1)+'</span>|<span class="p_badge"><b>Min Rating : </b>'+statistics['min_rating'].toFixed(1)+'</span>|<span class="p_badge"><b>Avg. Rating : </b>'+statistics['avg_rating'].toFixed(1)+'</span></div>');

                        for(var i=0;i<data_json['data'].length;i++){
                            var date = new Date(data_json['data'][i]['created_at']);
                            var newDate = (date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate());
                            data_json['data'][i]['created_at'] = newDate;
                        }

                        return JSON.stringify(data_json);
                    },
                },                
                columns: [
                    @foreach (config('app.hotel_master_header_key') as $key => $value) { data: '{{$key}}', name: '{{$key}}' }, @endforeach
                ]
            });

            $('#filter_apply').on('click', function(e) {
                oTable.draw();
            });

            $('#clear_filter').on('click',function(){
                $('.flat-icheck').iCheck('uncheck');
                $(".select2-country").val('').trigger('change');
                $(".select2-city").val('').trigger('change');
                $("#self_verified").val('').trigger('change');
                $("#guest_favourite").val('').trigger('change');
                $("#created_at_to").val('');
                $("#created_at_from").val('');
                $('#min_rating').val('');
                $('#max_rating').val('');
                oTable.draw();
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

            $('#hotel_master tbody').on('click', '.hotel_equip_popup', function() {
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
        });
    </script>
@endsection