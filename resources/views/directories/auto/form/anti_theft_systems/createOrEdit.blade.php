@extends('layouts.frame')

@section('title')

    {{!empty($antiTheftSystem) ? 'Редактирование' : 'Создание'}} противоугонной системы

@stop

@section('content')

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Название</label>
        <input data-id="{{(isset($antiTheftSystem)) ? $antiTheftSystem->id : 0}}" name="antiTheftSystemName" style="width: 75%" type="text" class="form-control" value="{{(isset($antiTheftSystem)) ? $antiTheftSystem->title : ''}}">
    </div>

@stop

@section('footer')

    <style>
        .btn {
            float: unset;
        }
    </style>

    <div style="min-height: 30px; display: flex;">
        @if(!empty($antiTheftSystem))
            <button type="submit" name="button-delete" class="btn btn-danger btn-save">Удалить</button>
        @endif
        <button style="margin-left: auto;" type="submit" name="button-save" class="btn btn-primary btn-save">Сохранить</button>
    </div>

@stop

@section('js')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.querySelector('button[name="button-save"]').addEventListener('click', () => {
                let antiTheftSystem = document.body.querySelector('input[name="antiTheftSystemName"]');
                let antiTheftSystemName = antiTheftSystem.value;
                let antiTheftSystemId = antiTheftSystem.dataset.id;
                loaderShow();
                $.post("{{route('anti-theft-system-save')}}", {antiTheftSystemId: antiTheftSystemId, antiTheftSystemName: antiTheftSystemName}, function (response) {
                    response = JSON.parse(response);
                    if (response.status === true) {
                        closeFancyBoxFrame();
                        window.parent.getDirectories('anti-theft-system')
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
                    let antiTheftSystem = document.body.querySelector('input[name="antiTheftSystemName"]');
                    let antiTheftSystemId = antiTheftSystem.dataset.id;
                    loaderShow();
                    $.post("{{route('anti-theft-system-delete')}}", {antiTheftSystemId: antiTheftSystemId}, function (response) {
                        response = JSON.parse(response);
                        if (response.status === true) {
                            closeFancyBoxFrame();
                            window.parent.getDirectories('anti-theft-system')
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
