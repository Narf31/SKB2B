<table id="tableControl" class="tov-table">
    <thead>
        <tr>
            <td>СТРАХОВАЯ СУММА</td>
            <td>СТРАХОВАЯ ПРЕМИЯ</td>
        </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Directories\Products\Data\DGO\Dgo::INSURANCE_AMOUNT as $key => $_conf)

        @php
            $baseRate = \App\Models\Directories\Products\Data\DGO\BaseRateDgo::getBaseRateList($key);
        @endphp

        <tr>
            <td>
                {{($_conf)}}
            </td>
            <td>
                {{ Form::text("conf[$key][payment_total]", titleFloatFormat($baseRate->payment_total, 0, 1), ['class' => 'form-control sum']) }}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>


