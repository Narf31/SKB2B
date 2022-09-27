@extends('layouts.frame')

@section('title')

    {{$scoring->title}}

@stop

@section('content')

    <div style="height: 300px;width: 100%" class="pull-left">
        <div>
            @php
                dump($json);
            @endphp
        </div>
    </div>

@stop

@section('footer')


@stop

