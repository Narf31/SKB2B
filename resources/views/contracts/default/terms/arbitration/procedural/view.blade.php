
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
            <span class="view-label">Заказчик (СРО)</span>
            <span class="view-value">{{($contract->data->cro?$contract->data->cro->title:'')}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Кол-во жалоб</span>
            <span class="view-value">{{$contract->data->count_complaints}}</span>
        </div>

        <div class="clear"></div>


        @php
            $procedure = $contract->data->procedure;
        @endphp

        <div class="view-field">
            <span class="view-label">Номер и дата дела</span>
            <span class="view-value">{{($procedure)? $procedure->title : ''}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Организация</span>
            <span class="view-value">{{($procedure)? $procedure->organization_title : ''}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">ИНН / ОГРН</span>
            <span class="view-value">{{($procedure)? $procedure->inn : ''}} / {{($procedure)? $procedure->ogrn : ''}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Адрес</span>
            <span class="view-value">{{($procedure)? $procedure->address : ''}}</span>
        </div>

        <div class="view-field">
            <label class="control-label">Описание</label>
            {!! ($procedure)?$procedure->content_html:'' !!}
        </div>

    </div>



</div>


<script>

    function initTerms() {





    }



</script>