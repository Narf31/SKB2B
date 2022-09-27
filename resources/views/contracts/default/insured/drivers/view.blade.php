
<div class="row form-horizontal" >
    <h2 class="inline-h1">Водители</h2>
    <br/><br/>

    @if((int)$contract->data->is_multidriver == 0)
    @foreach($insurers as $insurer)
        <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">{{$insurer->title}} ({{collect([0=>"муж.", 1=>'жен.'])[$insurer->sex]}} {{((int)date('Y')-(int)date('Y', strtotime($insurer->birthdate)))}} лет)</span>
                <span class="view-value">{{setDateTimeFormatRu($insurer->birthdate, 1)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Водительское удостоверение {{$insurer->doc_serie}} {{$insurer->doc_number}}</span>
                <span class="view-value">Дата выдачи {{getDateFormatRu($insurer->doc_date)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Водительское удостоверение</span>
                <span class="view-value">{{$insurer->doc_serie}} {{$insurer->doc_number}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Дата выдачи</span>
                <span class="view-value">{{getDateFormatRu($insurer->doc_date)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Начало стажа ({{$insurer->expyear}} лет)</span>
                <span class="view-value">{{getDateFormatRu($insurer->exp_date)}}</span>
            </div>

            <div class="clear"></div>

            <div class="divider"></div><br/>
        </div>


    @endforeach
    @else

        <h3 class="type_multidriver_1">Договор будет заключен на условиях неограниченного списка лиц, допущенных к управлению</h3>
        @if(isset($contract->data->is_only_spouses) && (int)$contract->data->is_only_spouses == 1)
            <h3 class="type_multidriver_1">Допущены только супруги</h3>
        @endif

        @if(isset($contract->data->calc_data))
            @php
                $calc_data = new stdClass();
                $calc_data->birthdate_year = '';
                $calc_data->birthdate_year_l = '';
                $calc_data->exp_year = '';
                $calc_data->exp_year_l = '';
                $calc_data->type_multidriver = 1;
                if($contract->data->calc_data && strlen($contract->data->calc_data) > 5){
                    $calc_data = json_decode($contract->data->calc_data);
                }

            @endphp

            @if((int)$calc_data->type_multidriver == 0)
                <h3 class="type_multidriver_1" style="color: red;">Возраст {{$calc_data->birthdate_year}} лет; Стаж {{$calc_data->exp_year}} лет;</h3>
            @endif
        @endif
    @endif

</div>





<script>


    function initStartInsureds(){


    }




</script>