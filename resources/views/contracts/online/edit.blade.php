@extends('layouts.app')

@section('content')


    @if(View::exists("contracts.product.{$contract->product->slug}.main.{$type}"))

        @if($type == 'edit')

            @if($contract->product->is_common_calculation == 1)

            <form id="product_form" class="product_form">
                @include("contracts.product.{$contract->product->slug}.main.{$type}", ['contract'=>$contract])
            </form>

            {{--Документы--}}
            @if(!$contract->program || ($contract->program && $contract->program->slug != "calculator") )

                @include('contracts.default.documentation.edit', ['contract'=>$contract])

            @endif
            <div class="page-heading">

                <span class="btn btn-success btn-left" onclick="saveContractAndCalc('{{$contract->id}}', 0);">Сохранить как черновик</span>
                <span class="btn btn-primary btn-right" onclick="calcContract('{{$contract->id}}');">Сохранить и рассчитать</span>
            </div>

            <br/><br/><br/><br/>

            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">
                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row form-horizontal" id="offers">
                                @if(View::exists("contracts.default.tariff.products.{$contract->product->slug}"))
                                    @include("contracts.default.tariff.products.{$contract->product->slug}", ['contract'=>$contract])
                                @else
                                    @include('contracts.default.tariff.edit', ['contract'=>$contract])

                                @endif



                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @else

                @include("contracts.product.{$contract->product->slug}.main.{$type}", ['contract'=>$contract])

            @endif


        @else

            @include("contracts.product.{$contract->product->slug}.main.{$type}", ['contract'=>$contract, 'view_damages'=>1])

        @endif
    @else
        <p>Оформление невозможно. Форма для продукта отсутствует</p>
    @endif

@stop


