@extends('adminlte::page')
@section('title')
    Url Stats
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Url's<small>stat</small></h1>
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
                                        <th>Log Count</th>
                                        <th>Total Urls</th>
                                        <th>Fail Count</th>
                                        <th>Success Count</th>
                                        <th>Run Count</th>
                                        <th>Date</th>
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
                searching: false,
                processing: true,
                serverSide: true,
                select: {
                    style: 'multi'
                },
                dom: "<'row'<'col-sm-2'li><'col-sm-10'f><'col-sm-10'p>>rt<'bottom'ip><'clear'>",
                ajax: {
                        url: "{!! route('url_stats.index.getData') !!}",
                },
                columns: [
                    { data: 'property_url', name: 'property_url'},
                    { data: 'log_count', name: 'log_count'},
                    { data: 'total_urls', name: 'total_urls' },
                    { data: 'fail_count', name: 'fail_count' },
                    { data: 'success_count', name: 'success_count' },
                    { data: 'run_count', name: 'run_count' },
                    { data: 'date', name: 'date' },
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