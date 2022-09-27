@extends('layouts.frame')

@section('title')

    {{!empty($mark) ? 'Редактирование' : 'Создание'}} модели

@stop

@section('content')

    <style>
        .category-selector {
            width: 75%;
        }
    </style>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Большой риск</label>
        <input name="modelRisky" style="width: unset;" type="checkbox" class="form-control" {{!empty($model) ? $model->is_risky === 1 ? 'checked="checked"' : '' : ''}}">
    </div>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Марка <span class="required">*</span></label>
        {{Form::select("markIsn", $marks->pluck('title', 'isn'), !empty($model) ? $model->mark_id : 3366, ['class' => 'form-control mark-selector'])}}
    </div>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Название</label>
        <input data-id="{{!empty($model) ? $model->id : 0}}" name="modelName" style="width: 75%" type="text" class="form-control" value="{{!empty($model->title) ? $model->title : ''}}">
    </div>

@stop

@section('footer')

    <style>
        .btn {
            float: unset;
        }
    </style>

    <div style="min-height: 30px; display: flex;">
        @if(!empty($model))
            <button type="submit" name="button-delete" class="btn btn-danger btn-save">Удалить</button>
        @endif
        <button style="margin-left: auto;" type="submit" name="button-save" class="btn btn-primary btn-save">Сохранить</button>
    </div>

@stop

@section('js')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.querySelector('button[name="button-save"]').addEventListener('click', () => {
                let markIsn = document.body.querySelector('select[name="markIsn"]').value;
                let model = document.body.querySelector('input[name="modelName"]');
                let modelName = model.value;
                let modelId = model.dataset.id;
                let modelRisky = document.body.querySelector('input[name="modelRisky"]').checked;
                loaderShow();
                $.post("{{route('model-save')}}", {markIsn: markIsn, modelName: modelName, modelId: modelId, modelRisky: modelRisky}, function (response) {
                    response = JSON.parse(response);
                    if (response.status === true) {
                        closeFancyBoxFrame();
                        window.parent.getDirectories('models');
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
                    let model = document.body.querySelector('input[name="modelName"]');
                    let modelId = model.dataset.id;
                    loaderShow();
                    $.post("{{route('model-delete')}}", {modelId: modelId}, function (response) {
                        response = JSON.parse(response);
                        if (response.status === true) {
                            closeFancyBoxFrame();
                            window.parent.getDirectories('models');
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
