@extends('layouts.frame')


@section('title')

    {{$general->title}} -

    @if($general->type_id == 0)
        {{setDateTimeFormatRu($general->data->birthdate, 1)}}
    @else
        {{$general->data->inn}} - {{$general->data->ogrn}}
    @endif

    <a target="_blank" href="{{url("/general/subjects/edit/{$general->id}")}}" class="btn btn-info pull-right">
        <i class="fa fa-cogs"></i>
    </a>

@stop

@php
    $state = $general->getView(auth()->user());
@endphp

@section('content')


    {{ Form::open(['url' => url("/general/subjects/frame/{$general->id}"), 'method' => 'post', 'class' => 'row form-horizontal']) }}
    <input type="hidden" name="contract_id" value="{{$contract_id}}"/>
    <input type="hidden" name="subjects" value="{{$subjects}}"/>

    @if($general->type_id == 0)
        @include("general.subjects.info.fl.data.{$state}")
    @else
        @include("general.subjects.info.ul.data.{$state}")
    @endif


    {{Form::close()}}


@stop


@section('footer')

    @if($state == 'edit')
    <button onclick="saveClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
    @endif


@endsection


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        function saveClients()
        {

            if(validate()){

                submitForm();

            }

        }


        $(function(){

            initDataSubjects();

            @if($contract_id && (int)$contract_id > 0)
                window.parent.setGeneralSubjects('{{$subjects}}');
            @endif


        });



    </script>

@endsection