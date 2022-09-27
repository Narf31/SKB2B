@extends('client.layouts.app')

@section('head')

@append

@section('content')


    @if(View::exists("client.contracts.product.{$contract->product->slug}.main.view"))

        @include("client.contracts.product.{$contract->product->slug}.main.view", ['contract'=>$contract])


    @else
        <p>Оформление невозможно. Форма для продукта отсутствует</p>
    @endif

    <script>

        var CONTRACT_TOKEN = "{{$contract->md5_token}}";
        var CONTRACT_URL = '{{urlClient('/contracts/online')}}';

    </script>

@endsection


