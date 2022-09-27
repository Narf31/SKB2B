
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

        @if($contract->data->territory_id && isset(\App\Models\Directories\Products\Data\Kasko\Standard::TERRIRORY[(int)$contract->data->territory_id]))
            <div class="view-field">
                <span class="view-label">Территория страхования</span>
                <span class="view-value">
                    {{\App\Models\Directories\Products\Data\Kasko\Standard::TERRIRORY[(int)$contract->data->territory_id]}}
                </span>
            </div>
        @endif

        @if($contract->data->limit_indemnity_id && isset(\App\Models\Directories\Products\Data\Kasko\Standard::LIMIT_INDEMNITY[(int)$contract->data->limit_indemnity_id]))
            <div class="view-field">
                <span class="view-label">Территория страхования</span>
                <span class="view-value">
                    {{\App\Models\Directories\Products\Data\Kasko\Standard::LIMIT_INDEMNITY[(int)$contract->data->limit_indemnity_id]}}
                </span>
            </div>
        @endif

        <div class="view-field">
            <span class="view-label">Скидка за счет КВ %</span>
            <span class="view-value">{{ titleFloatFormat($contract->data->official_discount, 0, 1) }}
            </span>
        </div>



        @if($contract->data->franchise_id && isset(\App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE[(int)$contract->data->franchise_id]))
            <div class="view-field">
                <span class="view-label">Франшиза</span>
                <span class="view-value">
                    {{\App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE[(int)$contract->data->franchise_id]}} -
                    {{ (isset(\App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE_NUMBER[$contract->data->franchise_number_id])) ? \App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE_NUMBER[$contract->data->franchise_number_id]: '' }}
                </span>
            </div>

        @else

            <div class="view-field">
                <span class="view-label">Франшиза</span>
                <span class="view-value">
                     Нет
                </span>
            </div>

        @endif

        <div class="clear"></div>



    </div>



</div>


<div class="row form-horizontal" >
    <h2 class="inline-h1">Дополнительные условия

    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        @if(isset(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::IS_CREDIT[(int)$contract->data->is_auto_credit]))
            <div class="view-field">
                <span class="view-label">Кредитное авто</span>
                <span class="view-value">
                    {{\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::IS_CREDIT[(int)$contract->data->is_auto_credit]}}
                </span>
            </div>
        @endif

        @if(isset(\App\Models\Directories\Products\Data\Kasko\Standard::COATINGS_RISKS[(int)$contract->data->coatings_risks_id]))
            <div class="view-field">
                <span class="view-label">Покрытия и риски</span>
                <span class="view-value">
                {{\App\Models\Directories\Products\Data\Kasko\Standard::COATINGS_RISKS[(int)$contract->data->coatings_risks_id]}}
            </span>
            </div>
        @endif

        @if(isset(\App\Models\Directories\Products\Data\Kasko\Standard::REPAIR_OPTIONS[(int)$contract->data->repair_options_id]))
            <div class="view-field">
                <span class="view-label">Варианты ремонта</span>
                <span class="view-value">
                {{\App\Models\Directories\Products\Data\Kasko\Standard::REPAIR_OPTIONS[(int)$contract->data->repair_options_id]}}
            </span>
            </div>
        @endif

        @if(isset(\App\Models\Directories\Products\Data\Kasko\Standard::CIVIL_RESPONSIBILITY[(int)$contract->data->civil_responsibility_sum]))
            <div class="view-field">
                <span class="view-label">Гражданская ответственность</span>
                <span class="view-value">
                {{\App\Models\Directories\Products\Data\Kasko\Standard::CIVIL_RESPONSIBILITY[(int)$contract->data->civil_responsibility_sum]}}
            </span>
            </div>
        @endif

        <div class="view-field">
            <span class="view-label">GAP</span>
            <span class="view-value">{{ ((int)$contract->data->is_gap == 0) ? 'Нет' : 'Да' }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Аварийный Комиссар</span>
            <span class="view-value">{{ ((int)$contract->data->is_emergency_commissioner == 0) ? 'Нет' : 'Да' }}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Эвакуация ТС при ДТП</span>
            <span class="view-value">{{ ((int)$contract->data->is_evacuation == 0) ? 'Нет' : 'Да' }}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Сбор справок в случае необходимости</span>
            <span class="view-value">{{ ((int)$contract->data->is_collection_certificates == 0) ? 'Нет' : 'Да' }}</span>
        </div>



        <div class="clear"></div>



    </div>



</div>

<script>

    function initTerms() {





    }



</script>