@extends('layouts.frame')

@section('title')

    Инструкция


@endsection

@section('content')

    {{ Form::model($info, ['url' => url("/directories/products/{$product_id}/edit/info/{$type_id}/{$id}/edit"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <div class="col-sm-12">
            {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder'=>'Название']) }}
        </div>
    </div>

    <div class="form-group">
        <textarea id="content" type="text" class="form-control" name="info_text" >
            {{$info->info_text}}
        </textarea>
    </div>


    {{Form::close()}}

@endsection

@section('footer')

    @if((int)$id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteItem('/directories/products/{{$product_id}}/edit/info/{{$type_id}}/delete/', '{{ $id }}')">{{ trans('form.buttons.delete') }}</button>
    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@endsection

@section('js')

    <script src="/plugins/ckeditor/ckeditor.js"></script>
    <script>


        $(function () {
            CKEDITOR.replace('content')
        });




    </script>


@endsection