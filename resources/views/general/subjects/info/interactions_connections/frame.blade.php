@extends('layouts.frame')


@section('title')

    {{$general->title}}

@stop


@section('content')


    {{ Form::open(['url' => url("/general/subjects/edit/{$general->id}/action/interactions-connections/{$ic_id}/"), 'method' => 'post', 'class' => 'row form-horizontal']) }}

    <input type="hidden" name="type" value="{{$type}}"/>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Организация <span class="required">*</span></label>
        <div class="col-sm-7">


            {{Form::text("general_organization_title", ($ic->general_organization?$ic->general_organization->title:''), ['class' => 'form-control searchGeneralOrganization', 'data-set-id'=>"general_organization_id"]) }}
            <input type="hidden" name="general_organization_id" id="general_organization_id" value="{{$ic->general_organization_id}}"/>

        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Должность <span class="required">*</span></label>
        <div class="col-sm-7">
            {{Form::text("job_position", $job_position, ['class' => 'form-control validate'])}}
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Дата начало отношений <span class="required">*</span></label>
        <div class="col-sm-7">
            {{Form::text("date_from", setDateTimeFormatRu($ic->date_from, 1), ['class' => 'form-control format-date validate'])}}
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-5 control-label">Дата завершения отношений</label>
        <div class="col-sm-7">
            {{Form::text("date_to", setDateTimeFormatRu($ic->date_to, 1), ['class' => 'form-control format-date '])}}
        </div>
    </div>

    <div class="clear"></div>


    {{Form::close()}}


@stop


@section('footer')


    <button onclick="saveClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>


@endsection


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        $(function () {

            searchGeneralOrganization();
            formatDate();

        });

        function saveClients()
        {

            if(validate()){

                submitForm();

            }

        }


        /**
         * Форматирование поля с датой
         **/
        function formatDate() {
            var configuration = {
                timepicker: false,
                format: 'd.m.Y',
                yearStart: 1900,
                scrollInput: false
            };



            $.datetimepicker.setLocale('ru');
            $('input.format-date').datetimepicker(configuration).keyup(function (event) {
                if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
                    var pattern = new RegExp("[0-9.]{10}");
                    if (pattern.test($(this).val())) {
                        $(this).datetimepicker('hide');
                        $(this).datetimepicker('show');
                    }
                }
            });
            $('input.format-date').each(function () {
                var im = new Inputmask("99.99.9999", {
                    "oncomplete": function () {
                    }
                });
                im.mask($(this));
            });
        }



    </script>

@endsection