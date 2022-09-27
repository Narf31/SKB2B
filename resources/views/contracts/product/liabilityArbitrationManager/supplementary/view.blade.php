<form id="product_form" class="product_form" >



    <div class="form-equally col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="page-heading">
            <h2 class="inline-h1">Условия продления договора

                <span class="pull-right">{{\App\Models\Contracts\ContractsSupplementary::STATUS[$supplementary->status_id]}}
                    @if($supplementary->status_id == 2 && $supplementary->matching)
                        - {{\App\Models\Contracts\Matching::STATYS[$supplementary->matching->status_id]}}
                    @endif
                </span>

            </h2>
        </div>

        <br/>



        <div class="view-field">
            <span class="view-label">Дата заключеия</span>
            <span class="view-value">{{setDateTimeFormatRu($supplementary->sign_date)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата начала</span>
            <span class="view-value">{{setDateTimeFormatRu($supplementary->begin_date)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата окончания</span>
            <span class="view-value">{{setDateTimeFormatRu($supplementary->end_date)}}</span>
        </div>


        {{--Участники комиссионного вознаграждения--}}
        @include('contracts.default.managers.liabilityArbitrationManager.view', [
            'contract_id'=>$contract->id,
            'contract'=>$supplementary,
        ])


        <br/>


        <div class="page-heading">
            <h2 class="inline-h1">Тарифы</h2>
        </div>

        <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <br/>

                <div class="view-field">
                    <span class="view-label">Оригинальнальный тариф: {{titleFloatFormat($supplementary->data->original_tariff)}}</span>
                    <span class="view-value">
                    {{titleFloatFormat($contract->data->original_payment_total)}}
                </span>
                </div>

                <div class="view-field">
                    <span class="view-label">Базовый тариф: {{titleFloatFormat($supplementary->data->base_tariff)}}</span>
                    <span class="view-value">{{titleFloatFormat($supplementary->data->base_payment_total)}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Желаемый тариф: {{titleFloatFormat($supplementary->data->manager_tariff)}}</span>
                    <span class="view-value" >
                   {{titleFloatFormat($supplementary->data->manager_payment_total)}}
                </span>
                </div>

                @if($supplementary->status_id == 2 && $supplementary->matching && $supplementary->matching->status_id == 2)
                    <a class="btn btn-info pull-left" href="{{url("/contracts/online/{$contract->id}/supplementary/{$supplementary->number_id}/set-edit")}}">Редактировать</a>

                @endif

                @if($supplementary->status_id == 2 && $supplementary->matching && $supplementary->matching->status_id == 4)
                    <span class="btn btn-primary pull-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/supplementary/{$supplementary->number_id}/release")}}')">Выпустить</span>

                @endif
            </div>
        </div>





    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="page-heading">
            <h2 class="inline-h1">Условия договора
                <span class="pull-right">
                    {{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}

                    @if($contract->statys_id == 2)
                        @if($contract->calculation && $contract->calculation->matching)
                            - {{\App\Models\Contracts\Matching::STATYS[$contract->calculation->matching->status_id]}}

                            @if($contract->calculation->matching->status_id == 2)
                                <span data-intro='Редактировать договора!' onclick="editStatusContract('{{$contract->id}}')" >
                                    <i class="fa fa-edit" style="cursor: pointer;color: green;"></i>
                                </span>
                            @endif
                        @endif
                    @elseif($contract->statys_id == 4 && $contract->bso)
                        {{$contract->bso->bso_title}}
                    @endif
                </span>
            </h2>
        </div>


        <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <br/>
            <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">


                <div class="view-field">
                    <span class="view-label">Дата заключеия</span>
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

                @if($contract->installment_algorithms)
                    <div class="view-field">
                        <span class="view-label">Алгоритм рассрочки</span>
                        <span class="view-value">{{$contract->installment_algorithms->info->title}}</span>
                    </div>
                @endif

                <div class="view-field">
                    <span class="view-label">Тип договора</span>
                    <span class="view-value">{{collect([0=>"Первичный", 1=>'Пролонгация'])[$contract->is_prolongation]}}</span>
                </div>

                @if($contract->is_prolongation == 1)

                    <div class="view-field">
                        <span class="view-label">Договор пролонгации</span>
                        <span class="view-value">{{$contract->prolongation_bso_title}}</span>
                    </div>

                @endif

                <div class="view-field">
                    <span class="view-label">Заказчик (СРО)</span>
                    <span class="view-value">{{($contract->data->cro)?$contract->data->cro->title:''}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Страхователь</span>
                    <span class="view-value">{{($contract->data->general_insurer)?$contract->data->general_insurer->title:''}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Тип договора</span>
                    <span class="view-value">{{\App\Models\Directories\Products\Data\LiabilityArbitrationManager::TYPE_AGR[$contract->data->type_agr_id]}}</span>
                </div>

                @if($contract->data->type_agr_id == 1)
                    <div class="view-field">
                        <span class="view-label">Кол-во текущих процедур</span>
                        <span class="view-value">{{\App\Models\Directories\Products\Data\LiabilityArbitrationManager::CURRENT_PROCEDURES[$contract->data->count_current_procedures]}}</span>
                    </div>

                @endif

                @if($contract->data->type_agr_id == 2)
                    <div class="view-field">
                        <span class="view-label">Процедура</span>
                        <span class="view-value">{{$contract->data->procedure->title}}</span>
                    </div>

                @endif


                <div class="view-field-custom">
                    <span class="view-label-custom">Страховая сумма</span>
                    <span class="view-value-custom">{{titleFloatFormat($contract->insurance_amount)}}</span>
                </div>


            </div>

        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size: 15px;">
            @if(sizeof($contract->masks))
                @foreach($contract->masks as $mask)
                    <a href="{{$mask->getUrlAttribute()}}" target="_blank">{{$mask->original_name}}</a> /
                @endforeach
            @endif
        </div>


        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @include('contracts.default.payments.view', ["contract" => $contract, 'payments' => $contract->payments])
                </div>

            </div>
        </div>



    </div>


</form>



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


    @include('contracts.product.liabilityArbitrationManager.supplementary_js')



    <script>

        function initTerms() {



        }

    </script>

@stop

