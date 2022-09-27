@extends('layouts.app')

@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Прием передача БСО</h2>

        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                        <input id="bso_cart_id" value="{{$bso_cart_id}}" type="hidden"/>

                        <table class="bso_up_header controls" style="width: 400px">
                            <tr>
                                <td style="width: 150px;">Тип</td>
                                <td>
                                    {{ Form::select('bso_cart_type', $bso_cart_type->prepend('Выберите значение', 0), $bso_cart->bso_cart_type, ['class' => 'form-control select2-ws', 'id'=>'bso_cart_type']) }}
                                </td>
                            </tr>
                            <tr id="tr_tp" style="display: none;">
                                <td>Точка продаж</td>
                                <td>
                                    {{ Form::select('tp', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), $bso_cart->tp_id, ['class' => 'form-control select2-ws tp', 'id'=>'tp']) }}
                                </td>
                            </tr>
                            <tr id="tr_tp_new" style="display: none;">
                                <td>Новоя точка продаж</td>
                                <td>
                                    {{ Form::select('tp_new', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), $bso_cart->tp_new_id, ['class' => 'form-control select2-ws tp_new', 'id'=>'tp_new']) }}
                                </td>
                            </tr>


                            <tr id="tr_user_id_to" style="display: none;">
                                <td valign="top">Агент-получатель</td>
                                <td valign="top">
                                    {{ Form::select('user_id_to', $agents->prepend('Выберите значение', 0), $bso_cart->user_id_to, ['class' => 'form-control user_id_to select2', 'id'=>'user_id_to']) }}

                                    <span class="agent_to_span"></span>
                                    <div class="agent_to_ban_text"></div>
                                </td>
                            </tr>
                            <tr>


                            <tr id="tr_tp_bso" style="display: none;">
                                <td>Сотрудник</td>
                                <td>
                                    {{ Form::select('tp_bso_manager', $bso_manager, $bso_cart->tp_bso_manager_id, ['class' => 'form-control select2-ws tp_bso_manager', 'id'=>'tp_bso_manager']) }}
                                </td>
                            </tr>


                            <tr id="tr_button" style="display: none;">
                                <td>&nbsp;</td>
                                <td><input type="button" value="Далее" id="cart_create" class="btn btn-primary"/></td>
                            </tr>
                        </table>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="agent_info">
                        </div>
                    </div>

                </div>

            </div>
        </div>


        @if($bso_cart_id > 0)

        <div class="block-main">
            <div class="block-sub">

                    @if($bso_cart->cart_state_id == 0)
                    <div class="row">
                        @include('bso.transfer_old.edit_carts', ['bso_cart'=>$bso_cart])
                    </div>
                    @elseif($bso_cart->cart_state_id == 1)
                        @foreach($bso_acts as $key => $act)
                            {{$key+1}}. <a href="/bso_acts/show_bso_act/{{$act->id}}/" target="_blank">{{$act->act_name}}</a><br/>
                        @endforeach
                    @endif

            </div>
        </div>

        @endif

    </div>
@stop

@section('js')


    <script src="/js/bso/transfer.js"></script>



    <style>
        .new_tr {
            display: none;
        }

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

        .agent_to_ban_text {
            margin-top: 10px;
            color: red;
        }
    </style>

@stop