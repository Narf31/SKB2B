@extends('layouts.frame')

@section('title')

    {{!empty($category) ? 'Редактирование' : 'Создание'}} категории

@stop

@section('content')

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Актуальная</label>
        <input name="categoryActual" style="width: unset;" type="checkbox" class="form-control" {{!empty($category) ? $category->is_actual === 1 ? 'checked="checked"' : '' : ''}}">
    </div>

    <div style="display: flex; padding: 10px;" class="form-horizontal">
        <label style="display: block; width: 25%;" class="control-label">Название</label>
        <input data-id="{{!empty($category) ? $category->id : 0}}" name="categoryName" style="width: 75%" type="text" class="form-control" value="{{!empty($category->title) ? $category->title : ''}}">
    </div>

@stop

@section('footer')

    <style>
        .btn {
            float: unset;
        }
    </style>

    <div style="min-height: 30px; display: flex;">
        @if(!empty($category))
            <button type="submit" name="button-delete" class="btn btn-danger btn-save">Удалить</button>
        @endif
        <button style="margin-left: auto;" type="submit" name="button-save" class="btn btn-primary btn-save">Сохранить</button>
    </div>

@stop

@section('js')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.querySelector('button[name="button-save"]').addEventListener('click', () => {
                let category = document.body.querySelector('input[name="categoryName"]');
                let categoryName = category.value;
                let categoryId = category.dataset.id;
                let categoryActual = document.body.querySelector('input[name="categoryActual"]').checked;
                loaderShow();
                $.post("{{route('category-save')}}", {categoryId: categoryId, categoryName: categoryName, categoryActual: categoryActual}, function (response) {
                    response = JSON.parse(response);
                    if (response.status === true) {
                        closeFancyBoxFrame();
                        window.parent.getDirectories('categories');
                    } else {
                        flashMessage('danger', response.message);
                    }
                }).always(function() {
                    loaderHide();
                });
            });

            let buttonDelete = document.body.querySelector('button[name="button-delete"]');
            if (buttonDelete !== null) {
                buttonDelete.addEventListener('click', () => {
                    let category = document.body.querySelector('input[name="categoryName"]');
                    let categoryId = category.dataset.id;
                    loaderShow();
                    $.post("{{route('category-delete')}}", {categoryId: categoryId}, function (response) {
                        response = JSON.parse(response);
                        if (response.status === true) {
                            closeFancyBoxFrame();
                            window.parent.getDirectories('categories');
                        } else {
                            flashMessage('danger', response.message);
                        }
                    }).always(function() {
                        loaderHide();
                    });
                });
            }

        });

    </script>
@stop
