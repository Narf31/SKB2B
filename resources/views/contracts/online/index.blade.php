@extends('layouts.app')

@section('content')

<div class="row form-horizontal" id="main_container"  data-intro='Выберите продукт страхования!'>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:15px;">
        <div class="policy-blocks">
            
            @if($products->get()->count())
            <div class="policy-b">
                <h3>Категория</h3>
                <ul id="productCategoryMenuList" class="p-tabs">
                    @foreach($categories as $category)
                    @if($loop->first)
                    @php 
                    $currrentCategory = 'current';
                    @endphp

                    @else
                    @php 
                    $currrentCategory = '';
                    @endphp

                    @endif

                    <li class="category category-{{$category->id}}">
                        <a onclick="selectCategory('{{$category->id}}');" href="#" class="{{$currrentCategory}}" data-category='{{$category->id}}'>{{$category->title}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="policy-b2">
                <h3>Продукт</h3>
                <div id="productMenuList" class="p-tabs-panes">

                    @foreach($products->get()->groupBy('category_id') as $i => $group)
                    <ul class="p-tabs2 product-category-{{$i}}" style="">
                        @foreach($group as $product)
                        <li>
                            <a href="javascript:void(0);" product-group="[{{$product->id}}]" data-progarm="{{$product->programs->count()}}" onclick="selectProduct({{$product->id}}); return false;" data-product="{{$product->id}}" id="product_{{$product->id}}" class="">{{$product->title}}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                </div>
            </div>

            <div class="policy-b2" id="view_programs" style="display: none;">
                <h3>Программы</h3>
                <div id="programsMenuList" class="p-tabs-panes">
                    @foreach($products->get() as $i => $product)
                        <ul class="p-tabs2 product-program-{{$product->id}}" style="">
                            @foreach($product->programs()->where('is_actual', 1)->orderBy('sort')->get() as $program)
                                <li>
                                    <a href="javascript:void(0);" data-program="{{$program->id}}" onclick="selectProgram({{$program->id}}); return false;" data-product="{{$product->id}}">{{$program->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>



            <div id="productDescriptionMenuList" class="policy-b3">
                <h3>Описание продукта</h3>
                <div class="p-tabs-panes2">
                    @foreach($products->get() as $product)
                    @if($loop->first) 
                    @php($selected = "")
                    @else
                    @php($selected = "hidden")
                    @endif
                    <div class="product-description product-description-{{$product->id}} {{$selected}}">
                        <p>{{$product->description ? $product->description : 'Отсутствует'}}</p>
                        <a href="{{url("contracts/online/{$product->id}/create/")}}" class="btn btn-primary" data-toggle="tooltip" data-original-title="Начать новый расчет по продукту">Создать договор</a>
                    </div>

                        @foreach($product->programs()->where('is_actual', 1)->get() as $program)

                            <div class="product-description product-description-program-{{$program->id}} hidden">
                                <p>{{$program->description ? $program->description : 'Отсутствует'}}</p>
                                <a href="{{url("contracts/online/{$product->id}/create/?program={$program->id}")}}" class="btn btn-primary" data-toggle="tooltip" data-original-title="Начать новый расчет по продукту">Создать договор</a>
                            </div>
                        @endforeach


                    @endforeach
                </div>
            </div>
            @else
            <p>Продукты для оформления отсутствуют</p>
            @endif
        </div>
    </div>
</div>


<div data-intro='Сохраненные черновики находятся тут!'>

@include("contracts.online.draft")

</div>

@stop

@section('head')
    <link rel="stylesheet" href="/css/online.css">
@append


@section('js')
    <script src="/js/online.js"></script>

    <script>

        $('document').ready(function () {
            selectCategory($('#productCategoryMenuList a:visible').eq(0).attr('data-category'));

            initDraft();

        });

    </script>


    <style>
    .cont-in {
        padding: 0 35px 35px 35px !important;
    }
</style>
@stop
