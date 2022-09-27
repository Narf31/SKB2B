@extends('layouts.frame')

@section('title')


    {{\App\Processes\Operations\Contracts\Settings\Kasco\Dopwhere::CATEGORY[$category]}}

@stop

@section('content')

    {{ Form::open(['url' => url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/dopwhere/{$category}/{$dopwhere_id}"), 'method' => 'post', 'class' => 'form-horizontal', 'style'=>'min-height: 500px;']) }}



    <div class="form-group">
        <label class="col-sm-4 control-label">Тариф %</label>
        <div class="col-sm-8">
            {{ Form::text('tarife', titleFloatFormat($dopwhere->tarife, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Группа</label>
        <div class="col-sm-8">
            <select id="group" name="group" class="select2-all" onchange="setViewGroup()">
                 @foreach($dopwheres as $key => $group)

                    @if(!isset($group['is_not_select']))
                        <option value="{{$key}}" @if($dopwhere_id > 0 && $dopwhere->group == $key) selected="selected" @endif>{{$group['title']}}</option>
                    @endif
                 @endforeach
            </select>
        </div>
    </div>



    <div id="view-form"></div>





    {{Form::close()}}

@stop

@section('footer')


    @if($dopwhere_id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteCoefficient()">{{ trans('form.buttons.delete') }}</button>

        <script>

            function deleteCoefficient() {
                if (!customConfirm()) return false;

                $.post('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/dopwhere/{$category}/{$dopwhere_id}")}}', {
                    _method: 'delete'
                }, function () {
                    window.parent.reloadSelect();
                });
            }

        </script>

    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>





@stop

@section('js')

    <script>

        $(function(){

            setViewGroup();


        });

        function setViewGroup() {
            group = $("#group").val();

            loaderShow();

            $.get("{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/dopwhere/{$category}/{$dopwhere_id}")}}/element/", {group:$("#group").val()}, function (response) {
                loaderHide();
                $("#view-form").html(response);

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

                initSelect2();


            }).done(function() {
                loaderShow();
            })
            .fail(function() {
                loaderHide();
            })
            .always(function() {
                loaderHide();
            });


        }

    </script>


@stop

