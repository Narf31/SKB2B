@extends('layouts.frame')

@section('title')

    Образцы документов


@stop

@section('content')


    <div class="row form-horizontal" >

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-height: 300px;overflow: auto">


            @if(sizeof($contract->masks))
                @foreach($contract->masks as $mask)

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <a href="{{$mask->getUrlAttribute()}}" class="pull-left" target="_blank">{{$mask->original_name}}</a>

                    </div>


                @endforeach
            @endif



        </div>



    </div>


@stop

@section('footer')


@stop

