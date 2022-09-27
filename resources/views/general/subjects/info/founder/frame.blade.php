@extends('layouts.frame')


@section('title')

    {{$general->title}}

@stop


@section('content')


    {{ Form::open(['url' => url("/general/subjects/edit/{$general->id}/action/founders/{$founder_id}/"), 'method' => 'post', 'class' => 'row form-horizontal']) }}



    <input type="hidden" name="type" value="{{$type}}"/>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Название/ФИО <span class="required">*</span></label>
        <div class="col-sm-7">

            {{Form::text("general_founders_title", ($founder->general?$founder->general->title:''), ['class' => 'form-control searchGeneralAll', 'data-set-id'=>"general_founders_id"]) }}
            <input type="hidden" name="general_founders_id" id="general_founders_id" value="{{$founder->general_founders_id}}"/>




        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Доля % <span class="required">*</span></label>
        <div class="col-sm-7">
            {{Form::text("share", ($founder->share)?titleFloatFormat($founder->share):'', ['class' => 'form-control sum validate'])}}
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Доля сумма <span class="required">*</span></label>
        <div class="col-sm-7">
            {{Form::text("share_sum", ($founder->share_sum)?titleFloatFormat($founder->share_sum):'', ['class' => 'form-control sum validate'])}}
        </div>
    </div>

    <div class="clear"></div>


    {{Form::close()}}


@stop


@section('footer')

    @if($founder_id > 0)
        <span onclick="deleteFounder()" class="btn btn-danger pull-left">Удалить</span>
    @endif

    <button onclick="saveClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>


@endsection


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        $(function () {

            $('.sum')
                .change(function () {
                    $(this).val(CommaFormatted($(this).val()));
                })
                .blur(function () {
                    $(this).val(CommaFormatted($(this).val()));
                })
                .keyup(function () {
                    $(this).val(CommaFormatted($(this).val()));
                });

            searchGeneralAll();

        });

        function saveClients()
        {

            if(validate()){

                submitForm();

            }

        }


        function deleteFounder() {
            if (!customConfirm()) return false;

            $.post('{{url("/general/subjects/edit/{$general->id}/action/founders/{$founder_id}")}}', {
                _method: 'delete'
            }, function () {
                parent_reload_tab();
            });
        }




    </script>

@endsection