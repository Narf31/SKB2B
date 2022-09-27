@extends('client.layouts.app')

@section('head')

@append

@section('content')


    @foreach($categories as $category)
    <div class="content__box" style="width: 100%;padding-bottom: 20px;">
        <div class="content__box-title seo__item">
            {{$category->title}}
        </div>
        <div class="calc__menu">
            @foreach($category->online_products as $product)

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <a href="{{urlClient("/contracts/online/{$product->id}/create/")}}" class="order-item">
                        <div class="order-title">
                            <span class="name"><b>{{$product->title}}</b></span>
                        </div>
                        <div class="divider"></div>

                        <div class="divider"></div>
                        <div class="order-contacts">
                            <div class="name">{{$product->description}}</div>
                        </div>

                        <div class="divider"></div>
                        <div class="order-contacts">
                            <span class="btn__round d-flex align-items-center justify-content-center">Оформить</span>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>
    </div>

    <div class="clear"></div>
    @endforeach




@endsection


