@extends('layouts.frame')

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        <h1><center>{{ $exception->getMessage()?: "Данный метод запрещён"}}</center></h1>
    </div>
@stop
