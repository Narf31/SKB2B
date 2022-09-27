
<div class="row form-horizontal" >
    <h2 class="inline-h1">Условия договора
        @if($contract->statys_id == 4)
            <span class=" pull-right" data-intro='Копировать договор!' onclick="copyContract('{{$contract->id}}')"><i class="fa fa-clone" style="cursor: pointer;color: green;"></i></span>
        @endif
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


        <div class="view-field">
            <span class="view-label">Кол-во текущих процедур</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\LiabilityArbitrationManager::CURRENT_PROCEDURES[$contract->data->count_current_procedures]}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Заказчик (СРО)</span>
            <span class="view-value">{{($contract->data->cro?$contract->data->cro->title:'')}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Кол-во жалоб</span>
            <span class="view-value">{{$contract->data->count_complaints}}</span>
        </div>

        <div class="clear"></div>



    </div>



</div>


<script>

    function initTerms() {





    }



</script>