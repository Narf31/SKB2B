<div class="row form-horizontal">
    <h2 class="inline-h1">
        @if($contract->bso)

            @if(isset($is_link) && $is_link == 1)
                <a href="{{url("/contracts/online/{$contract->id}")}}" target="_blank">{{$contract->bso->bso_title}}</a>

                - {{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}


            @else
                <span>{{$contract->bso->bso_title}}</span>

                - {{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}



            @endif

        @else

            {{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}

        @endif



        @if(!isset($is_link) || $is_link == 0)
                <span class=" pull-right" data-intro='Копировать договор!' onclick="copyContract('{{$contract->id}}')"><i class="fa fa-clone" style="cursor: pointer;color: green;"></i></span>

            @if($contract->statys_id == 3 || ($contract->statys_id == 2 && \App\Processes\Scenaries\Contracts\Matchings\MatchingsContract::checkStatus($contract) == true))
                <span class=" pull-right" data-intro='Редактировать' onclick="editStatusContract('{{$contract->id}}')"><i class="fa fa-edit" style="cursor: pointer;color: green;margin-right: 20px;"></i></span>

            @endif



            @if($contract->statys_id == 4)

                @if(auth()->user()->hasPermission('contracts', 'cancel_contract'))
                    <span data-intro='Аннулировать договор!' onclick="cancelContract('{{$contract->id}}')"><i class="fa fa-close" style="cursor: pointer;color: red;"></i></span>
                @endif



                @if($contract->end_date <= date('Y-m-d 00:00:00', strtotime("+60 day")))
                    <span  class="pull-right" style="margin-right: 20px;" data-intro='Пролонгировать договор!' onclick="prolongationContract('{{$contract->id}}')"><i class="fa fa-exchange" style="cursor: pointer;color: green;"></i></span>
                @endif


            @endif

        @endif


    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">ID Договора</span>
            <span class="view-value"><a href="{{url("/contracts/online/{$contract->id}")}}">{{$contract->id}}</a></span>
        </div>
        <div class="view-field">
            <span class="view-label">Программа</span>
            <span class="view-value">{{$contract->getProductAndProgramTitle()}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Агент</span>
            <span class="view-value">{{($contract->agent)?$contract->agent->name:''}} - {{ ($contract->agent)?$contract->agent->organization->title:""  }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Куратор</span>
            <span class="view-value">{{($contract->curator)?$contract->curator->name:''}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Страховая сумма</span>
            <span class="view-value">{{titleFloatFormat($contract->insurance_amount)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Страховая премия</span>
            <span class="view-value">{{titleFloatFormat($contract->payment_total)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">К оплате (с учетом скидки)</span>
            <span class="view-value">{{titleFloatFormat($contract->payment_total-($contract->payments()->sum('official_discount_total')))}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">История договора</span>
            <span class="view-value"><a href="javascript:void(0);" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/history")}}')">Посмотреть</a></span>
        </div>

        @if($contract->installment_algorithms)
        <div class="view-field">
            <span class="view-label">Алгоритм рассрочки</span>
            <span class="view-value">{{$contract->installment_algorithms->info->title}}</span>
        </div>
        @endif


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size: 25px;">
            @if(sizeof($contract->masks))
                @foreach($contract->masks as $mask)
                    <a href="{{$mask->getUrlAttribute()}}" target="_blank">{{$mask->original_name}}</a> /
                @endforeach


            @endif

            @if($contract->statys_id == 4)
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="btn btn-info pull-left" onclick="refreshMask({{$contract->id}})">Переформировать</span>
                </div>
            @endif

        </div>




    </div>
</div>

