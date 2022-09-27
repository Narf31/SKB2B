<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <input type="hidden" name="contract[arbitration][count_current_procedures]" value="2"/>

                    <input type="hidden" name="contract[begin_time]" value="00:00" />
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date-today valid_accept" id="begin_date_0" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата окончания <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[end_date]" class="form-control format-date-today valid_accept" id="end_date_0" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : ''}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Страховая сумма <span class="required">*</span></label>
                        {{ Form::text("contract[insurance_amount]", ($contract->insurance_amount > 0)? titleFloatFormat($contract->insurance_amount, 0, 1):'', ['class' => 'form-control sum valid_accept', 'id'=>'insurance_amount']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Алгоритм рассрочки</label>
                        {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws']) }}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control']) }}
                    </div>

                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" >
                        <label class="control-label">Заказчик (СРО)</label>
                        {{Form::text("contract[arbitration][cro_title]", ($contract->data->cro?$contract->data->cro->title:''), ['class' => 'form-control searchOrganization', 'data-set-id'=>"cro_id", 'id'=>'cro_title']) }}
                        <input type="hidden" class="valid_accept" data-parent="cro_title" name="contract[arbitration][cro_id]" id="cro_id" value="{{$contract->data->cro_id}}"/>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1">
                        <label class="control-label">Срочно</label><br/>
                        <input @if($contract->data->is_urgently == 1) checked="checked" @endif class="easyui-switchbutton clear_offers" data-options="onText:'Да',offText:'Нет'" name="contract[arbitration][is_urgently]" type="checkbox">
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1">
                        <label class="control-label">Стаж (лет) <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][experience]", $contract->data->experience, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во жалоб <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_complaints]", $contract->data->count_complaints, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во предупреждений <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_warnings]", $contract->data->count_warnings, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во штрафов <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_fines]", $contract->data->count_fines, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<div class="page-heading">
    <h2 class="inline-h1">Процедура</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row form-horizontal">

                    @php
                        $procedure = $contract->data->procedure;
                    @endphp
                    <input type="hidden" name="contract[arbitration][procedure_id]" value="{{($procedure)?$procedure->id:0}}"/>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" >
                        <label class="control-label">Номер и дата дела</label>
                        {{ Form::text("contract[procedure][title]", ($procedure)? $procedure->title : '', ['class' => 'form-control']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
                        <label class="control-label">Процедура банкротства</label>
                        {{ Form::select("contract[procedure][bankruptcy_procedures_id]", collect(\App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures::BANKRUPTCY_PROCEDURES), ($procedure)? $procedure->bankruptcy_procedures_id : 0, ['class' => 'form-control select2-ws']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3" >
                        <label class="control-label">ИНН</label>
                        {{ Form::text("contract[procedure][inn]", ($procedure)? $procedure->inn : '', ['class' => 'form-control party-autocomplete', 'id'=>'inn']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3" >
                        <label class="control-label">ОГРН</label>
                        {{ Form::text("contract[procedure][ogrn]", ($procedure)? $procedure->ogrn : '', ['class' => 'form-control party-autocomplete', 'id'=>'ogrn']) }}
                    </div>
                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                        <label class="control-label">Организация</label>
                        {{ Form::text("contract[procedure][organization_title]", ($procedure)? $procedure->organization_title : '', ['class' => 'form-control party-autocomplete', 'id'=>'organization_title']) }}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                        <label class="control-label">Адрес</label>
                        {{ Form::text("contract[procedure][address]", ($procedure)? $procedure->address : '', ['class' => 'form-control address-autocomplete']) }}
                        <input type="hidden" name="contract[procedure][latitude]" value="{{($procedure)? $procedure->latitude: ''}}" id="latitude"/>
                        <input type="hidden" name="contract[procedure][longitude]" value="{{($procedure)? $procedure->longitude:''}}" id="longitude"/>
                    </div>
                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <label class="control-label">Описание</label>
                        <textarea id="content" type="text" style="height: 300px;" class="form-control" name="contract[procedure][content]">{{($procedure)?$procedure->content_html:''}}</textarea>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="/plugins/ckeditor/ckeditor.js"></script>

<script>



    function initTerms() {

        formatTime();
        searchOrganization();

        //CKEDITOR.replace('content');

        if($('*').is('.party-autocomplete')) {
            $(".party-autocomplete").suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "PARTY",
                count: 5,
                onSelect: function (suggestion) {
                    var data = suggestion.data;
                    var subjectType = $(this).data('party-type');


                    $('#organization_title').val(suggestion.value);
                    $('#inn').val(data.inn);
                    $('#ogrn').val(data.ogrn);


                }
            });
        }


        if($('*').is('.address-autocomplete')) {
            $(".address-autocomplete").suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "ADDRESS",
                count: 5,
                onSelect: function (suggestion) {
                    var data = suggestion.data;
                    var subjectType = $(this).data('address-type');

                    //$('#address').val(data.country);

                    $('#latitude').val(data.geo_lat);
                    $('#longitude').val(data.geo_lon);


                }
            });
        }



    }



    function searchOrganization() {
        $('.searchOrganization').suggestions({
            serviceUrl: "/suggestions/dadata/organization",
            token: "",
            type: "PARTY",
            count: 5,
            formatResult: function(e, t, n, i) {
                var s = this;
                e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
                return e;
            },

            onSelect: function (suggestion) {

                $("#"+$(this).data("set-id")).val(suggestion.id);

            }
        });
    }

</script>