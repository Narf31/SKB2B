@extends('layouts.frame')


@section('title')

    ФЛ

@stop

@section('content')

    <div style="min-height: 200px">
    {{ Form::open(['url' => url('/general/subjects/find'), 'method' => 'post', 'class' => 'row form-horizontal']) }}

    <input type="hidden" name="contract_id" value="{{$contract_id}}"/>
    <input type="hidden" name="subjects" value="{{$subjects}}"/>
    <input type="hidden" name="type" value="{{$type}}"/>
    <input type="hidden" name="sex" id="sex" value="0"/>




    <div class="col-sm-8">
        <label class="col-sm-12 control-label">ФИО</label>
        <div class="col-sm-12">
            {{ Form::text('title', '', ['class' => 'form-control fio-search validate']) }}
        </div>
    </div>


    <div class="col-sm-4">
        <label class="col-sm-12 control-label">Дата рождения</label>
        <div class="col-sm-12">
            {{ Form::text('birthdate', '', ['class' => 'form-control format-date validate']) }}
        </div>
    </div>


    {{Form::close()}}
    </div>

@stop


@section('footer')

    <button onclick="createClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.create') }}</button>

@endsection


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        function createClients()
        {

            if(validate()){

                submitForm();

            }

        }


        $(function(){


            $(".fio-search").suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "NAME",
                count: 5,
                onSelect: function (suggestion) {

                    var data = suggestion.data;


                    if(data.gender == 'FEMALE'){
                        $("#sex").val(1);
                    }else{
                        $("#sex").val(0);
                    }


                }
            });

            formatDate();


        });


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
    <script src="/js/online.js"></script>

@endsection