@extends('layouts.frame')

@section('title')

    {{!empty($color) ? 'Редактирование' : 'Создание'}} цвета

@stop

@section('content')

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Название</label>
        <input data-id="{{!empty($color) ? $color->id : 0}}" name="colorName" style="width: 75%" type="text" class="form-control" value="{{!empty($color->title) ? $color->title : ''}}">
    </div>

@stop

@section('footer')

    <style>
        .btn {
            float: unset;
        }
    </style>

    <div style="min-height: 30px; display: flex;">
        @if(!empty($color))
            <button type="submit" name="button-delete" class="btn btn-danger btn-save">Удалить</button>
        @endif
        <button style="margin-left: auto;" type="submit" name="button-save" class="btn btn-primary btn-save">Сохранить</button>
    </div>

@stop

@section('js')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.querySelector('button[name="button-save"]').addEventListener('click', () => {
                let color = document.body.querySelector('input[name="colorName"]');
                let colorName = color.value;
                let colorId = color.dataset.id;
                loaderShow();
                $.post("{{route('color-save')}}", {colorId: colorId, colorName: colorName}, function (response) {
                    response = JSON.parse(response);
                    if (response.status === true) {
                        closeFancyBoxFrame();
                        window.parent.getDirectories('colors');
                    } else {
                        flashMessage('danger', response.message)
                    }
                }).always(function() {
                    loaderHide();
                });
            });

            let buttonDelete = document.body.querySelector('button[name="button-delete"]');
            if (buttonDelete !== null) {
                buttonDelete.addEventListener('click', () => {
                    let color = document.body.querySelector('input[name="colorName"]');
                    let colorId = color.dataset.id;
                    loaderShow();
                    $.post("{{route('color-delete')}}", {colorId: colorId}, function (response) {
                        response = JSON.parse(response);
                        if (response.status === true) {
                            closeFancyBoxFrame();
                            window.parent.getDirectories('colors');
                        } else {
                            flashMessage('danger', response.message)
                        }
                    }).always(function() {
                        loaderHide();
                    });
                });
            }

        });

    </script>
@stop
