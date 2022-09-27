
<div id="dataBaseRate">
    <input type="hidden" value="{{$mark_id}}" name="mark_id"/>
    <input type="hidden" value="{{$model_id}}" name="model_id"/>
    <table class="tov-table">
        <tr>
            <th>Год</th>
            <th>БТС ущерб</th>
            <th>БТС тоталь %</th>
            <th>БТС хищение %</th>
        </tr>
        @foreach(getYearsKasko() as $key => $year)

            @php
                $baserate = \App\Models\Directories\Products\Data\Kasko\BaseRateKasko::getBaseRate($product->id, $program->id, $mark_id, $model_id, $key);
                if(!$baserate){
                    $baserate = new \stdClass();
                    $baserate->payment_damage = null;
                    $baserate->total = null;
                    $baserate->theft = null;
                }
            @endphp

            <tr>
                <td>{{$key}} - {{$year}}
                    <input type="hidden" value="{{$key}}" name="baserate[{{$key}}][year]"/>
                </td>
                <td>
                    {{ Form::text("baserate[{$key}][payment_damage]", titleFloatFormat($baserate->payment_damage, 0, 1), ['class' => 'form-control sum']) }}
                </td>
                <td>
                    {{ Form::text("baserate[{$key}][total]", titleFloatFormat($baserate->total, 0, 1), ['class' => 'form-control sum']) }}
                </td>
                <td>
                    {{ Form::text("baserate[{$key}][theft]", titleFloatFormat($baserate->theft, 0, 1), ['class' => 'form-control sum']) }}
                </td>
            </tr>
        @endforeach

    </table>
</div>
<br/>
<span class="btn btn-success pull-left" onclick="saveBaseRate()">Сохранить</span>

<script>


    function initBaseRate() {
        $('.sum')
            .change(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .blur(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .keyup(function () {
                $(this).val(CommaFormatted($(this).val()));
            });


    }

    function saveBaseRate()
    {

        loaderShow();


        $.post("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/auto/baserate/save", $('#dataBaseRate :input').serialize(), function (response) {


            if (Boolean(response.state) === true) {

                flashMessage('success', "Данные успешно сохранены!");


            }else {
                flashHeaderMessage(response.msg, 'danger');

            }

        }).always(function () {
            loaderHide();
        });


    }


</script>
