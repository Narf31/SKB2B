@extends('layouts.app')

@section('content')




    <div class="row form-horizontal" style="margin-top: 15px">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    @include('bso.items.info', ['bso' => $bso])
                </div>
            </div>
        </div>
    </div>



    @if($bso->bso_class_id !== 100)

        @if($bso->contract)
            <div class="page-heading">
                <h2 class="inline-h1">Договор</h2>
            </div>

            <div class="row form-horizontal" style="margin-top: 15px">
                <div class="block-main">
                    <div class="block-sub">
                        <div class="form-horizontal">

                            @include("contracts.product.{$bso->contract->product->slug}.main.view", ['contract'=>$bso->contract, 'type' => 'view'])

                        </div>
                    </div>
                </div>
            </div>



            <div class="page-heading">
                <h2 class="inline-h1">Убытки</h2>
            </div>


            <div class="row form-horizontal" style="margin-top: 15px">
                <div class="block-main">
                    <div class="block-sub">
                        <div class="form-horizontal product_form">

                            @include("orders.damages.info", ['damages' => $bso->contract->damages, 'is_link'=> 1])

                        </div>
                    </div>
                </div>
            </div>

        @endif

    @endif




@stop

@section('js')


    <script>

        $(function () {





        });






    </script>

@stop