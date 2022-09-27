@extends('layouts.frame')

@section('title')


    Контакты assistance


@stop

@section('content')

    {{ Form::open(['url' => url("/directories/products/{$product_id}/edit/special-settings/assistance_info/{$assistance_id}/edit"), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <div class="form-group">
        <label class="col-sm-4 control-label">Страна</label>
        <div class="col-sm-8">
            {{ Form::select('country_id', \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id')->prepend('По умолчанию', 0),$assistance->country_id, ['class' => 'form-control select2-all', 'required']) }}
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-4 control-label">Названия</label>
        <div class="col-sm-8">
            {{ Form::text('title', $assistance->title, ['class' => 'form-control', 'required']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Телефон</label>
        <div class="col-sm-8">
            {{ Form::text('phone', $assistance->phone, ['class' => 'form-control', 'required']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Примечания</label>
        <div class="col-sm-8">
            {{ Form::text('comments', $assistance->comments, ['class' => 'form-control', 'required']) }}
        </div>
    </div>



    {{Form::close()}}

@stop

@section('footer')

    @if($assistance_id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteAssistance('{{url("/directories/products/{$product_id}/edit/special-settings/assistance_info/{$assistance_id}/edit")}}')">{{ trans('form.buttons.delete') }}</button>

    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

    <script>

        function deleteAssistance(url) {
            if (!customConfirm()) return false;

            $.post(url, {
                _method: 'delete'
            }, function () {
                parent_reload();
            });
        }

    </script>

@stop

