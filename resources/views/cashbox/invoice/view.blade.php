@extends('layouts.app')


@section('content')


    @include("payments.invoice.head", ["invoice" => $invoice])

    @include("payments.invoice.body", ["invoice" => $invoice])

    @include("payments.invoice.info", ["invoice" => $invoice])


@stop




@section('js')
    <script>


        $(function(){

            initInvoce();

        });




    </script>

@endsection


