@extends('layouts.app')

@section('content')



    @if(View::exists("contracts.product.{$contract->product->slug}.supplementary.{$type}"))

        @include("contracts.product.{$contract->product->slug}.supplementary.{$type}", ['contract'=>$contract, 'supplementary' => $supplementary])

    @else
        <p>Оформление невозможно. Форма для продукта отсутствует</p>
    @endif

@stop


