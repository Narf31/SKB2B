@extends('layouts.app')

@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Прием передача БСО</h2>

        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="row form-horizontal">
                        @if($bso_cart->cart_state_id == 0)
                            @include('bso.transfer_old.menu_carts', ['bso_cart'=>$bso_cart])
                        @elseif($bso_cart->cart_state_id == 1)
                            @foreach($bso_acts as $key => $act)
                                {{$key+1}}. <a href="/bso_acts/show_bso_act/{{$act->id}}/" target="_blank">{{$act->act_name}}</a><br/>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">

                    <div class="row form-horizontal">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Тип</span>
                                <span class="view-value">{{$bso_cart->type->title}}</span>
                            </div>

                            <div class="view-field">
                                <span class="view-label">Точка продаж</span>
                                <span class="view-value">{{$bso_cart->point_sale->title}}</span>
                            </div>

                            @if($bso_cart->bso_cart_type == 1)
                            <div class="view-field">
                                <span class="view-label">Агент-получатель</span>
                                <span class="view-value">123</span>
                            </div>

                            @endif

                            @if($bso_cart->bso_cart_type == 2)
                            <div class="view-field">
                                <span class="view-label">Новоя точка продаж</span>
                                <span class="view-value">{{$bso_cart->new_point_sale->title}}</span>
                            </div>

                            <div class="view-field">
                                <span class="view-label">Сотрудник-получатель</span>
                                <span class="view-value">{{$bso_cart->tp_bso_manager->name}}</span>
                            </div>

                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>




    </div>
@stop

@section('js')

<script>


    $(function () {



    });









</script>



@stop