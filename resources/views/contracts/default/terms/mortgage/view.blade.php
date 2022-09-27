
<div class="row form-horizontal" >
    <h2 class="inline-h1">Условия договора

    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Дата заключения</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->sign_date)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата начала</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->begin_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата окончания</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->end_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Тип договора</span>
            <span class="view-value">{{collect([0=>"Первичный", 1=>'Пролонгация'])[$contract->is_prolongation]}}
                @if($contract->is_prolongation == 1) {{$contract->prolongation_bso_title}} @endif
            </span>
        </div>


        @if($contract->data->is_transition)
            <div class="view-field">
                <span class="view-label">Переход из другой компании</span>
                <span class="view-value">
                    {{\App\Models\Directories\Products\Data\Kasko\Standard::TRANSITION[(int)$contract->data->is_transition]}}
                </span>
            </div>
        @endif


        <div class="view-field">
            <span class="view-label">Скидка за счет КВ %</span>
            <span class="view-value">{{ titleFloatFormat($contract->data->official_discount, 0, 1) }}
            </span>
        </div>


        <div class="clear"></div>


        <div class="view-field">
            <span class="view-label">Банк</span>
            <span class="view-value">{{$contract->data->bank->title}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Сумма кредита/ОСЗ</span>
            <span class="view-value">{{titleFloatFormat($contract->insurance_amount, 0, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Срок кредита мес.Банк</span>
            <span class="view-value">{{titleFloatFormat($contract->data->credit_term, 0, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Ставка кредита </span>
            <span class="view-value">{{titleFloatFormat($contract->data->loan_rate, 0, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Адрес объекта страхования</span>
            <span class="view-value">{{$contract->data->address}}</span>
        </div>

    </div>



</div>


@if((int)$contract->data->is_life == 1)
<div class="row form-horizontal" >
    <h2 class="inline-h1">Жизнь</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Профессия</span>
            <span class="view-value">{{$contract->data->profession}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Отклонение по здоровью</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_HEALTH_DEVIATION[$contract->data->type_health_deviation]}} {{ $contract->data->health_deviation }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Спорт</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_SPORT[$contract->data->type_sport]}} {{ $contract->data->sport }}</span>
        </div>

        <div class="clear"></div>

    </div>

</div>
@endif

@if((int)$contract->data->is_property == 1)
<div class="row form-horizontal" >
    <h2 class="inline-h1">Имущество</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Площадь</span>
            <span class="view-value">{{titleFloatFormat($contract->data->area, 0, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Год постройки</span>
            <span class="view-value">{{$contract->data->year_construction}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Материал стен или перекрытий из горючих материалов?</span>
            <span class="view-value">{{($contract->data->is_combustible_material == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Наличие кап.ремонта/перепланировки?</span>
            <span class="view-value">{{($contract->data->is_availability_repair == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Проводятся ремонтные работы?</span>
            <span class="view-value">{{($contract->data->is_repair_work_progress == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="clear"></div>

    </div>

</div>
@endif

@if((int)$contract->data->is_title == 1)
<div class="row form-horizontal" >
    <h2 class="inline-h1">Титул</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Документ, подтверждающий право собственности</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\Mortgage\Mortgage::DOCUMENT_OWNER[$contract->data->document_owner]}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Ограничение права собственности</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_SPORT[$contract->data->type_ownership_restriction]}} {{ $contract->data->ownership_restriction }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Рыночная стоимость</span>
            <span class="view-value">{{titleFloatFormat($contract->data->price, 0 ,1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Срок по титулу (мес.)</span>
            <span class="view-value">{{titleNumberFormat($contract->data->title_period, 0 ,1)}}</span>
        </div>


        <div class="view-field">
            <span class="view-label">Сделка проводится по доверенности?</span>
            <span class="view-value">{{($contract->data->is_deal_proxy == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Среди собственников есть младше 18 и старше 65 лет?</span>
            <span class="view-value">{{($contract->data->is_owners_age == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Объект в собственности менее 3 лет?</span>
            <span class="view-value">{{($contract->data->is_object_owner_age == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Среди собственников есть юридические лица?</span>
            <span class="view-value">{{($contract->data->is_owner_ul == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Единовременная оплата ?</span>
            <span class="view-value">{{($contract->data->is_owner_payment == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Отсутствует согласие супруга / супруги?</span>
            <span class="view-value">{{($contract->data->is_not_agreement == 1) ? 'Да' : 'Нет'}}</span>
        </div>

        <div class="clear"></div>

    </div>

</div>
@endif



<script>

    function initTerms() {





    }



</script>