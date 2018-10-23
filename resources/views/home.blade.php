@extends('adminlte::page')

@section('title', 'LimeHome')

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                    <span class="custome_badge bg-warning script_status">
                        Scraper Status:- {{ ucwords($custom_config->script_status) }}
                        @if($custom_config->script_status == 'running')
                            ({{ $custom_config->started_at->toDateTime()->format('M d h:i A') }})
                        @else
                            ({{ $custom_config->ended_at->toDateTime()->format('M d h:i A') }})
                        @endif
                    </span>
                </div>
            </div>
        </section>
        <!-- end of content header (page header) -->
        <!-- main content-->
    
        <section class="content">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Users</span>
                            <span class="info-box-number">{{$user_count}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Property</span>
                            <span class="info-box-number"><span class="font-normal">Active:- </span>{{$p_active_count}} <br><span class="font-normal">Total:- </span>{{$p_total_count}}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5 col-sm-12 col-xs-12 text-right">
                    <div class="form-group">
                        @if($custom_config->scraper_active =='1')
                            <button class="btn btn-success">Scraper Active</button>
                        @else
                            <button class="btn btn-warning">Scraper In-active</button>
                        @endif    
                    </div>
                </div>

{{--                 <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                    <a class="btn btn-success" href="{{ route('restart_parser') }}">Restart Scraper</a>
                    <a class="btn btn-success" href="{{ route('stop_parser') }}">Stop Scraper</a>
                </div> --}}

            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="card stats_panel">
                  <div class="card-header">
                    <h3 class="card-title text-center">Parsing Stats</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <tbody><tr>
                        <th>Date</th>
                        <th>Total Property</th>
                        {{-- <th>Active Property</th> --}}
                        <th>Property Parsed</th>
                        <th>Property Pending</th>
                       {{-- <th>Success(%)</th> --}}
                       {{-- <th>Pending</th> --}}
                       {{-- <th>Price Parsed</th> --}}
                        {{-- <th>Room Details Parsed</th> --}}
                        {{-- <th>Availability Parsed</th> --}}
                      </tr>


                      @foreach($stats as $stat)
                          <tr>
                            <td>{{ $stat->date->toDateTime()->format('Y M d') }}</td>
                            <td>{{ $stat->total_property }}</td>
                           {{-- <td>{{ $stat->active_property }}</td> --}}
                            <td>{{ $stat->property_parsed }}</td>
                            <td>{{ $stat->property_pending }}</td>
                           {{-- <td>
                                <span class="custome_badge bg-success">{{ number_format(($stat->property_parsed*100)/$stat->active_property) }}%</span>
                            </td>
                            <td>
                              <span class="custome_badge bg-danger">{{ 100 - number_format(($stat->property_parsed*100)/$stat->active_property) }}%</span>
                            </td> --}}
                           {{-- <td>{{ number_format($stat->price_parsed,0 , '' , ',') }}</td> --}}
                            {{-- <td>{{ number_format($stat->room_details_parsed,0 , '' , ',') }}</td> --}}
                            {{-- <td>{{ number_format($stat->rooms_availability_parsed,0 , '' , ',') }}</td> --}}
                          </tr>              
                      @endforeach
                    </tbody></table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
        </section>
    </div>
@endsection