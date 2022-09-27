@extends('layouts.app')

@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Прием передача БСО</h2>

        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">
                        @if($bso_cart->cart_state_id == 0)
                            @include('bso.transfer.menu_carts', ['bso_cart'=>$bso_cart])
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
                                <input type="hidden" id="point_sale" value="{{$bso_cart->tp_id}}"/>
                            </div>

                            @if($bso_cart->bso_cart_type == 1)
                            <div class="view-field">
                                <span class="view-label">Агент-получатель</span>
                                <span class="view-value">{{$bso_cart->user_to->name}} - {{$bso_cart->user_to->organization->title}}</span>
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


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
        @if($bso_cart->cart_state_id == 0)
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div id="bsos">
                </div>

                <div id="rit_bsos" style="display: none;">
                </div>
                <br/><br/><br/><br/>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">

                    <a href="/bso/transfer/reserve_export?bso_cart_id={{$bso_cart->id}}" class="btn btn-primary btn-left doc_export_btn">Распечатать резервный акт</a>

                    <input type="button" class="btn  btn-success btn-left" id="b_transfer_bso" value="Передать БСО" />

                    <span class="btn btn-info btn-right" onclick="removeCar()">Удалить</span>

                    <br/><br/><br/>

                    <label>
                        <input type="checkbox" class="cb_right_column_style" checked />
                        Группировка по типам
                    </label>

                    <div id="bso_cart">
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>




    <style>

        .bso_table {
            font: 12px arial;
            border: 1px solid #777;
            border-collapse: collapse;
        }
        .bso_table td, th {
            border: 1px solid #777;
            padding: 5px;
            font: 12px arial;
        }

        .bso_table th {
            background-color: #EEE;
        }


        .bso_up_header {
            font: 12px arial;
            border: none;
            border-collapse: collapse;
            width: 100%;
        }
        .bso_up_header td {
            padding: 5px;
            border: none;
            font: 12px arial;
        }

        .bso_header {
            font: 12px arial;
            border: none;
            border-collapse: collapse;
            width: 100%;
        }
        .bso_header td {
            padding: 5px;
            border: none;
            font: 12px arial;
            background-color: #F3F3F3;
        }

        .bso_qty {
            width: 50px;
        }

        .type_selector, .series_selector {
            width: 120px;
        }



        .bso_number, .bso_blank_number {
            width: 160px;
        }

        .bso_number_td {
            width: 170px;
        }

        .error_div, .error_span {
            color: red;
        }

        .sk_header {
            font: bold 17px arial !important;
        }

        .center {
            text-align: center !important;
        }

        .right {
            text-align: right !important;
        }

        .gray {
            background-color: #EEE !important;
        }

        .remove_button, .remove_type_button, .remove_string_button, .remove_sk_button {
            cursor: pointer;
        }

        .remove_button{
            margin-left: 15px;
        }
        .remove_sk_button{
            padding-top: 6px;
        }
        input[type=button] {
            cursor: pointer;
        }

        #b_transfer_bso {
            background-color: red;
            color: white;
        }

        #print_reverve_act {
            background-color: green;
            color: white;
        }


    </style>


@stop
