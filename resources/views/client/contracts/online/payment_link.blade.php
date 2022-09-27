@extends('client.layouts.app')

@section('head')

@append

@section('content')

    <div class="page__title seo__item ">
        {{ $contract->product->title }} - {{ $contract->bso_title }}
    </div>

    <div class="row row__custom justify-content-center" style="min-height: 800px;">

        <div id="iframe_parent"></div>

    </div>

    <script>

        var CONTRACT_TOKEN = "{{$contract->md5_token}}";
        var CONTRACT_URL = '{{urlClient('/contracts/online')}}';

    </script>



@endsection


@section('js')

    <script src="https://paymo.ru/paymentgate/iframe/checkout.js"></script>
    <script src="/assets/client/js/payments/paymo.js"></script>

    <script>

        document.addEventListener("DOMContentLoaded", function (event) {

            openPaymoInvoice();

        });


    </script>

@endsection