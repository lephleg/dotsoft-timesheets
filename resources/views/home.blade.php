@extends('layout')

@section('content')
    <section>
        {{--<!-- Date -->--}}
        {{--<div class="form-group">--}}
            {{--<label>Date:</label>--}}

            {{--<div class="input-group date">--}}
                {{--<div class="input-group-addon">--}}
                    {{--<i class="fa fa-calendar"></i>--}}
                {{--</div>--}}
                {{--<input type="text" class="form-control pull-right" id="datepicker">--}}
            {{--</div>--}}
            {{--<!-- /.input group -->--}}
        {{--</div>--}}
        {{--<!-- /.form group -->--}}
        <div id='dailyDotsoftCalendar'></div>
    </section>
@stop
