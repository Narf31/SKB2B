

<div style="background-color: #F3F3F3; padding: 5px; margin-bottom: 10px;">
    <b>{{$bso_supplier_name}}</b>
    <span class="btn remove_sk_button btn-right" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
    <table class="bso_table" sk_user_id="2">
        <tr>
            <th>Тип</th>
            <th>Серия</th>
            <th>Кол-во</th>
            <th class="bso_number_td">№ полиса / квит. / сер.карт с</th>
            <th class="bso_number_td">№ по</th>
            <th>Удалить</th>
        </tr>


        <tr class="table_row" completed="0">
            <td>
                {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
                <div class="error_div"></div>
            </td>
            <td>
                <select class="series_selector form-control "></select>
            </td>
            <td>
                <input type="text" class="bso_qty intmask" />
                <div class="error_div"></div>
            </td>
            <td>
                <input type="text" class="bso_number" />
                <div class="error_div"></div>
            </td>
            <td><span class="bso_number_to">&nbsp;</span></td>

            <td style="text-align: center;">
                <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
            </td>
        </tr>

        <tr class="table_row" completed="0">
            <td>
                {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
                <div class="error_div"></div>
            </td>
            <td>
                <select class="series_selector form-control "></select>
            </td>
            <td>
                <input type="text" class="bso_qty intmask" />
                <div class="error_div"></div>
            </td>
            <td>
                <input type="text" class="bso_number" />
                <div class="error_div"></div>
            </td>
            <td><span class="bso_number_to">&nbsp;</span></td>

            <td style="text-align: center;">
                <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
            </td>
        </tr>


    </table>

    <input class="add_string_button btn btn-right" type="button" value="Добавить строку" style="cursor: pointer;" sk_user_id="2" />
</div>


<textarea class="new_tr" sk_user_id="2">
<td>
    {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
    <div class="error_div"></div>
</td>
<td>
    <select class="series_selector form-control "></select>
</td>
<td>
    <input type="text" class="bso_qty intmask" />
    <div class="error_div"></div>
</td>
<td>
    <input type="text" class="bso_number" />
    <div class="error_div"></div>
</td>
<td><span class="bso_number_to">&nbsp;</span></td>
<td style="text-align: center;">
    <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
</td>
</textarea>


