@extends('adminlte::page')
@section('title')
    Booking Logs
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel _booking_logs">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Booking<small>Logs</small></h1>
        </section>
        <!-- end of content header (page header) -->

        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered" id="url-stats-table">
                                <thead>
                                    <tr>
                                        <th class="th-1">Property Name/ Url</th>
                                        <th class="th-2">Status Code</th>
                                        <th class="th-3">Log</th>
                                        <th class="th-4">Created At</th>
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
            $.fn.dataTable.ext.errMode = 'none';
            var oTable = $('#url-stats-table').DataTable({
               "aLengthMenu": [5, 10, 25, 50, 100, 500, 1000],
               "iDisplayLength": 100,
                "sPaginationType" : "full_numbers",
                searching:false,
                processing: true,
                serverSide: true,
                select: {
                    style: 'multi'
                },
                dom: "<'row'<'col-sm-2'li><'col-sm-10'f><'col-sm-10'p>>rt<'bottom'ip><'clear'>",
                ajax: {
                        url: "{!! route('logs.index.getData') !!}",
                },
                columns: [
                    { data: 'property_url', name: 'property_url'},
                    { data: 'status_code', name: 'status_code' },
                    { data: 'log', name: 'log' },
                    { data: 'created_at', name: 'created_at' }
                ],
                columnDefs: [
                   // { "orderable": false, "targets": [4, 5, 6, 7, 8] },
                  //  { "orderable": true, "targets": [0, 1, 2, 3] }
                ],
               // "order": [[ 2, "desc" ]]
            });
        });
    </script>
@endsection