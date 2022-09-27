<table class="bso_table" style="width: 100%;">
    <tr>
        <td style="width: 45%; vertical-align: top;">
            <table class="bso_header">
                <tr>
                    <td colspan="2">
                        <label><input type="checkbox" class="cb_left_column_style" />Указывать диапазон БСО</label>
                    </td>
                </tr><tr id="sk_selector">
                    <td style="width: 160px;">Страховая компания</td>
                    <td>
                        {{ Form::select('sk_user_id', \App\Models\Directories\BsoSuppliers::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('Выберите значение', 0), 0, ['class' => 'form-control select2-all sk_user_id', 'id'=>'sk_user_id']) }}
                        <br/>
                        @if($bso_cart->bso_cart_type == 2 || $bso_cart->bso_cart_type == 3)
                            <input type="button" value="Вывести все БСО" id="show_all_bsos"/>
                        @endif
                        <input type="button" style="cursor: pointer;display: none;" value="Добавить" class="button_sk_selector"/>
                    </td>
                </tr>
                <tr class="tr_type_selector">
                    <td style="width: 160px;">Тип</td>
                    <td><select id="bso_type_id"></select></td>
                </tr>
                <tr class="tr_type_selector">
                    <td style="width: 160px;">Статус</td>
                    <td>
                        {{ Form::select('bso_state_id', \App\Models\BSO\BsoState::all()->pluck('title','id'), 0, ['id'=> 'bso_state_id']) }}
                    </td>
                </tr>
            </table>
            <div id="bsos">
            </div>
            <div id="rit_bsos" style="display: none;">
            </div>
            <input type="button" value="Поместить в корзину" style="cursor: pointer; display: none;" class="save_transmit_bso btn btn-left"/></td>

        <td style="vertical-align: top;">

            <a href="/bso/transfer/reserve_export?bso_cart_id={{$bso_cart->id}}" class="btn btn-primary btn-left doc_export_btn">Распечатать резервный акт</a>

            {{--

            <a href="/bso/xls/act/bso_carts_group_reserve_act/?bso_cart_id={{$bso_cart->id}}" class="btn  btn-primary btn-left" target="_blank" id="print_reverve_act">Сгруппированный акт</a>

            --}}
            <input type="button" class="btn  btn-success btn-left" id="b_transfer_bso" value="Передать БСО" />

            <br/><br/><br/>

            <label>
                <input type="checkbox" class="cb_right_column_style" />
                Группировка по типам
            </label>

            <div id="bso_cart">
            </div>
        </td>
    </tr>
</table>