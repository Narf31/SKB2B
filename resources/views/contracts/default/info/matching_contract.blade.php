<div class="row form-horizontal">
    <h2 class="inline-h1">
        Согласование
        @if($contract->calculation && $contract->calculation->matching)
            - {{\App\Models\Contracts\Matching::STATYS[$contract->calculation->matching->status_id]}}

            @if($contract->calculation->matching->status_id == 2)
                <span data-intro='Редактировать договора!' onclick="editStatusContract('{{$contract->id}}')" >
                    <i class="fa fa-edit" style="cursor: pointer;color: green;"></i>
                </span>
            @endif
        @endif



        <span class="pull-right" data-intro='История изменения договора!' onclick="openFancyBoxFrame('{{url("/contracts/online/$contract->id/action/history")}}')">
            <i class="fa fa-clock-o" style="cursor: pointer;color: #3387c3;"></i>
        </span>



    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Программа</span>
            <span class="view-value">{{$contract->getProductOrProgram()->title}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Агент</span>
            <span class="view-value">{{$contract->agent->name}} - {{ $contract->agent->organization->title  }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Куратор</span>
            <span class="view-value">{{($contract->curator)?$contract->curator->name:''}}</span>
        </div>

        @if($contract->installment_algorithms)
        <div class="view-field">
            <span class="view-label">Алгоритм рассрочки</span>
            <span class="view-value">{{$contract->installment_algorithms->info->title}}</span>
        </div>
        @endif

        @if(View::exists("contracts.default.info.tariff.{$contract->product->slug}"))
            @include("contracts.default.info.tariff.{$contract->product->slug}", ['contract'=>$contract])
        @else
            @include('contracts.default.info.tariff.default', ['contract'=>$contract])
        @endif


        @if($contract->calculation && $contract->calculation->matching && $contract->calculation->matching->status_id > 0)

            <div class="view-field">
                <span class="view-label">Андеррайтер</span>
                <span class="view-value">{{$contract->calculation->matching->check_user->name}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Дата время</span>
                <span class="view-value">{{setDateTimeFormatRu($contract->calculation->matching->updated_at)}}</span>
            </div>



            @if($contract->calculation->matching->status_id == 5 || $contract->calculation->matching->status_id == 2)
                <span style="font-size: 18px;color: red;">{{$contract->calculation->matching->comments}}</span>
            @endif

            @if($contract->calculation->matching->status_id == 4)
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

                        <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>

                        <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
                    </div>
                </div>
            @endif

        @else
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>
                </div>
            </div>
        @endif

    </div>



</div>

