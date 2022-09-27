@extends('layouts.frame')


@section('title')

    Лимиты {{$user->name}}

@stop

@section('content')


    {{ Form::open(['url' => url("/users/limit/?user_id={$user->id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}



    <div class="form-horizontal">

        @if(sizeof($products))

            @foreach($products as $product)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{$product->title}}</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="product_id[]" value="{{$product->id}}"/>
                        {{ Form::text('max_limit[]', $user->getUserLimitBSOToProduct($product->id, 0),  ['class' => 'form-control status_user_id']) }}
                    </div>
                </div>
            @endforeach

        @else
            {{ trans('form.empty') }}
        @endif






    </div>




    {{Form::close()}}




@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop




@section('js')
    <script>
        $(function () {


        });


        
        
    </script>
@append