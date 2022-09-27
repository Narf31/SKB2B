@extends('layouts.app')



@section('content')




    {{ Form::open(['url' => url('/directories/products'), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}

    @include('directories.products.form')

    {{Form::close()}}

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

    <button onclick="submitForm()" type="submit" class="btn btn-primary pull-left">{{ trans('form.buttons.save') }}</button>


@endsection




@section('js')

    <script>

        $(function(){

            $('#slug').on('change', function () {
                viewProgram();
            });

            viewProgram();


        });


        function submitForm() {

            var success = true;
            var form = $('.form-horizontal');

            form.find('input[required=required], select[required=required]').each(function () {
                var valid = $(this).val() != '';
                $(this).toggleClass('has-error', !valid);
                if (!valid) {
                    success = false;
                }
            });

            if (success) {
                form.submit();
            }

        }

        $('input[name="for_inspections"]').on('change', function () {
            if ($(this).val() == 0){
                $('#inspection_temple_act').hide();
            } else{
                $('#inspection_temple_act').show();

            }
        });


    </script>

@endsection