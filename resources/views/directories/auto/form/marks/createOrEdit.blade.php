@extends('layouts.frame')

@section('title')

    {{!empty($mark) ? 'Редактирование' : 'Создание'}} марки

@stop

@section('content')

    <style>
        .category-selector {
            width: 75%;
        }
    </style>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Категория <span class="required">*</span></label>
        {{Form::select("categoryIsn", $categories->pluck('title', 'isn'), !empty($mark->category_id) ? $mark->category_id : $categories->first()->isn, ['class' => 'form-control category-selector'])}}
    </div>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Название</label>
        <input data-id="{{!empty($mark) ? $mark->id : 0}}" name="markName" style="width: 75%" type="text" class="form-control" value="{{!empty($mark->title) ? $mark->title : ''}}">
    </div>

@stop

@section('footer')

    <style>
        .btn {
            float: unset;
        }
    </style>

    <div style="min-height: 30px; display: flex;">
        @if(!empty($mark))
            <button type="submit" name="button-delete" class="btn btn-danger btn-save">Удалить</button>
        @endif
        <button style="margin-left: auto;" type="submit" name="button-save" class="btn btn-primary btn-save">Сохранить</button>
    </div>

@stop

@section('js')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.querySelector('button[name="button-save"]').addEventListener('click', () => {
                let categoryIsn = document.body.querySelector('select[name="categoryIsn"]').value;
                let mark = document.body.querySelector('input[name="markName"]');
                let markName = mark.value;
                let markId = mark.dataset.id;
                loaderShow();
                $.post("{{route('mark-save')}}", {categoryIsn: categoryIsn, markName: markName, markId: markId}, function (response) {
                    response = JSON.parse(response);
                    if (response.status === true) {
                        closeFancyBoxFrame();
                        window.parent.getDirectories('marks');
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
                    let mark = document.body.querySelector('input[name="markName"]');
                    let markId = mark.dataset.id;
                    loaderShow();
                    $.post("{{route('mark-delete')}}", {markId: markId}, function (response) {
                        response = JSON.parse(response);
                        if (response.status === true) {
                            closeFancyBoxFrame();
                            window.parent.getDirectories('marks');
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
