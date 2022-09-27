@extends('layouts.frame')


@section('title')

    {{$general->title}}

@stop


@section('content')


    {{ Form::open(['url' => url("/general/subjects/edit/{$general->id}/document/{$doc_id}"), 'method' => 'post', 'class' => 'row form-horizontal']) }}

    @include("general.subjects.info.documents.edit", [
        'docs' => $DOC_TYPE,
        'is_main' => (int)$document->is_main,
        'index' => 0,
        'doc' => $document,
    ])

    {{Form::close()}}


@stop


@section('footer')

    @if(/*(int)$document->is_main != 1 &&*/ $doc_id > 0)
        <span onclick="deleteDocument()" class="btn btn-danger pull-left">Удалить</span>
    @endif

    <button onclick="saveClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>


@endsection


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        $(function () {


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

        function deleteDocument() {

            if (!customConfirm()) return false;

            $.post('{{url("/general/subjects/edit/{$general->id}/document/{$doc_id}")}}', {
                _method: 'delete'
            }, function () {
                parent_reload_tab();
            });

        }

    </script>

@endsection