@extends('adminlte::page')
@section('title')
    Property Urls
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel _property_url">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Property<small>Url's</small></h1>
        </section>
        <!-- end of content header (page header) -->

        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                            <h3 class="box-title">Upload CSV File</h3>
                        </div> --}}
                            
                            <div class="box-body">
                                {!! Form::open(array('url' => 'property_url', 'method' => 'POST', 'enctype' =>'multipart/form-data', 'class' => 'form-inline') ) !!}
                                <div class="form-group">
                                    {!! Form::label('property_url_file', 'Upload CSV File (city,url)') !!}
                                    {!! Form::file('property_url_file', array('required' => 'required', 'class' => 'form-control')) !!}
                                </div>
                                {!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
                                {!! Form::close() !!}
                            </div>
                    </div>
                </div>
            </div>
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
                            <table class="table table-bordered" id="property-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Id</th>
                                        <th>Hotel Title/Url</th>
                                        <th>City</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Num Guest</th>
                                        <th>Stay Length</th>
                                        <th>Select Parsing Interval (in days)</th>
                                        <th>Links</th>                                       
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <input type="button" class="btn btn-danger" id="delete_property" value="Delete Selected Properties">
            </div>
        </section>
        <!-- end of main content-->
    </div>
    <!-- end of content wrapper. contains page content -->
@endsection
@section('js')
    <script type="text/javascript">
        $(function() {
            $.fn.dataTable.ext.errMode = 'none';
            var oTable = $('#property-table').DataTable({
                "aLengthMenu": [5, 10, 25, 50, 100, 500, 1000],
                "iDisplayLength": 100,
                "sPaginationType" : "full_numbers",
                searching:false,
                processing: true,
                serverSide: true,
                select: {
                    style: 'multi'
                },
                scrollY: "300px",
                scrollX: true,
                scrollCollapse: true,
                dom: "<'row'<'col-sm-2'li><'col-sm-10'f><'col-sm-10'p>>rt<'bottom'ip><'clear'>",
                ajax: {
                        url: "{!! route('property_url.index.getData') !!}",
                    data: function (d) {
                        d.countries = $("#countries").val();
                        d.cities = $("#cities").val();
                    },
                    dataFilter: function(response) {
                        var data_json = JSON.parse(response);
                        
                        for(var i=0;i<data_json['data'].length;i++){
                            var create_date = new Date(data_json['data'][i]['created_at']);
                            var created_date = (create_date.getFullYear() + '-' + (create_date.getMonth() + 1) + '-' + create_date.getDate());
                            data_json['data'][i]['created_at'] = created_date;

                            var update_date = new Date(data_json['data'][i]['created_at']);
                            var updated_date = (update_date.getFullYear() + '-' + (update_date.getMonth() + 1) + '-' + update_date.getDate());
                            data_json['data'][i]['updated_at'] = updated_date;
                        }
                        return JSON.stringify(data_json);
                    },
                },
                columns: [
                    // { data: 'url', name: 'url', 'render' : function ( data, type, row) { return '<a target="_blank" href="' + data + '">' + data + '</a>'; } },
                    { data: 'select', width: '5px', render: function ( data, type, row ) {
                            if ( type === 'display' ) {
                                return '<input type="checkbox" class="editor-active">';
                            }
                            return data;
                        },
                    },
                    { data: 'id', name: 'id', visible: false},
                    { data: 'hotel_name', name: 'hotel_name'},
                    { data: 'city', name: 'city'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'num_guest', name: 'num_guest'},
                    { data: 'stay_length', name: 'stay_length'},
                    { data: 'parse', name: 'parse'},
                    { data: 'link', name: 'link'},
                ],
                columnDefs: [
                    { "orderable": false, "targets": [0, 5, 6, 7, 8, 9] },
                    { "orderable": true, "targets": [1, 2, 3, 4] }
                ],
                "order": [[ 2, "desc" ]]
            });

            $('#filter_apply').on('click', function(e) {
                oTable.draw();
            });

            $('#clear_filter').on('click',function(e){
                $(".select2-country").val('').trigger('change');
                $(".select2-city").val('').trigger('change');
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
                        console.log(data)
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
                        console.log(data)
                        return {
                          results: data
                        };
                    },
                    cache: true
                }
            });

            // $('#property-table').on('change', '.update_status', function (e) { 
            //     e.preventDefault();
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     var data = {};
            //     data._id = $(this).attr('prop_id');
            //     var parse_interval = $(this).val();
            //     data.parse_interval = parse_interval;

            //     if(data.parse_interval != '' || data.parse_interval !=null){
            //         $.ajax({
            //             type: "POST",
            //             url: "{!! URL::to('property_url/updatestatus') !!}",
            //             dataType: "json",
            //             data: data,
            //             success:function(data){
            //                 oTable.draw();
            //                 console.log("this is success msg");
            //             },
            //             error: function(error) {
            //                 console.log("this is error msg");
            //                 console.log(error);
            //             }
            //         });
            //     } else{
            //         alert("Please Add Message.");
            //     }
            // });
            
            // $('#property-table').on('change', '.update_guest', function (e) { 
            //     e.preventDefault();
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     var data = {};
            //     data._id = $(this).attr('prop_id');
            //     var guest_count = $(this).val();
            //     data.num_guest = guest_count;

            //     if(data.num_guest != '' || data.num_guest !=null){
            //         $.ajax({
            //             type: "POST",
            //             url: "{!! URL::to('property_url/updateguest') !!}",
            //             dataType: "json",
            //             data: data,
            //             success:function(data){
            //                 oTable.draw();
            //                 console.log("this is success msg");
            //             },
            //             error: function(error) {
            //                 console.log("this is error msg");
            //                 console.log(error);
            //             }
            //         });
            //     } else{
            //         alert("Please Add Message.");
            //     }
            // });

            $('#property-table').on('click', '.update_stay_length', function (e) { 
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = {};
                var id = $(this).attr('id');
                var stay_length = $('.input_'+id).val();
                var guest_count = $('.update_guest_'+id).val();                
                var parse_interval = $('.update_status_'+id).val();
                data._id = id;
                data.stay_length = stay_length;
                data.parse_interval = parse_interval;
                data.num_guest = guest_count;
                
                if(data.stay_length != '' || data.stay_length !=null){
                    $.ajax({
                        type: "POST",
                        url: "{!! URL::to('property_url/updatestaylength') !!}",
                        dataType: "json",
                        data: data,
                        success:function(data){
                            oTable.draw();
                            $.alert({
                                title: 'Alert!',
                                content: 'Details are updated successfully!',
                            });
                        },
                        error: function(error) {
                            $.alert({
                                title: 'Alert!',
                                content: 'Details cannot be updated.',
                            });
                        }
                    });
                } else{
                    alert("Please Add Message.");
                }
            });

            // $('#property-table').on('click', '.delete_property', function (e) { 
            //     e.preventDefault();
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     var data = {};
            //     data._id = $(this).attr('id');
            //     $.ajax({
            //         type: "POST",
            //         url: "{!! URL::to('property_url/deleteProperty') !!}",
            //         dataType: "json",
            //         data: data,
            //         success:function(data){
            //             $.alert({
            //                 title: 'Alert!',
            //                 content: 'Property Url Deleted Successfully',
            //             });
            //             oTable.draw();
            //         },
            //         error: function(error) {
            //             console.log("this is error msg");
            //             console.log(error);
            //         }
            //     });
            // });

            $('#property-table tbody').on( 'click', 'tr input[type="checkbox"]', function () {
                var row = $(this).closest('tr');
                row.toggleClass('selected-row');
            });

            $('#delete_property').on('click',function(e){
                var ids = $.map(oTable.rows('.selected-row').data(), function (item) {
                    return item['id']
                });
                var info = oTable.page.info();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "{!! URL::to('property_url/deleteProperty') !!}",
                    dataType: "json",
                    data: {'id': ids},
                    success:function(data){
                        var delete_count = data.delete_count;
                        if(delete_count > 0){
                            $.alert({
                                title: 'Alert!',
                                content: delete_count+' url deleted!'
                            });
                        }
                        else{
                            $.alert({
                                title: 'Alert!',
                                content: 'Please select urls'
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                oTable.page(info.page).draw('page');
            });
        });
    </script>
@endsection