@extends('adminlte::page')
@section('title')
    Config
@endsection

@section('content')
    <!-- content wrapper. contains page content -->
    <div class="content-panel">
        <!-- content header (page header) -->
        <section class="content-header">
            <h1>Edit Config<small></small></h1>
        </section>
        <!-- end of content header (page header) -->

        <!-- main content-->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                            {!! Form::open(array('url' => 'config', 'method' => 'POST') ) !!}
                            <div class="box-body">
                                <div class="form-group row">
                                    <label class="col-sm-3">Parse Interval :</label>
                                    <input class="col-sm-3" type="text" name="parsing_interval" value="{{ $custom_config->parsing_interval }}">
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Thread Count :</label>
                                    <input class="col-sm-3" type="text" name="thread_count" value="{{ $custom_config->thread_count }}">
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">No. Of Guest :</label>
                                    <input class="col-sm-3" type="text" name="number_of_guests" value="{{ $custom_config->number_of_guests }}">
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Stay Length :</label>
                                    <input class="col-sm-3" type="text" name="str_length_stay" value="{{ $custom_config->str_length_stay }}">
                                </div>                                
                                <div class="form-group row">
                                    <label class="col-sm-3">Scraper Status</label>
                                    <input name="scraper_active" type="checkbox" @if($custom_config->scraper_active =='1') checked="checked" @endif>
                                    <label>Active</label>
                                </div>
                            </div>
                            
                            <div class="box-footer">
                                @if(auth()->user()->user_type =='super_admin')
                                <button type="submit" class="btn btn-primary">Upload</button>
                                @endif
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
        <!-- end of main content-->
    </div>
    <!-- end of content wrapper. contains page content -->
@endsection
@section('js')
    <!-- <script type="text/javascript">
        $(document.body).on('click', '.update_status' ,function(){
            if($(this).attr('title')=='Deactivate')
            {
                $("#scraper_active").val('0');
            }
            else{
                $("#scraper_active").val('1');
            }
        });
    </script> -->
@endsection