@php
    $export_data = null;

    if(isset($contract->data->export_data) && strlen($contract->data->export_data) > 0){
        $export_data = json_decode($contract->data->export_data, true);
    }

@endphp

@if($export_data)

    <div class="page-heading">
        <h2 class="inline-h1">Участники КВ
            @if($contract->statys_id == 4 && $contract->data->type_agr_id == 1)
                <span class=" pull-right" data-intro='Пролонгировать договор!' onclick="prolongationContract('{{$contract->id}}')"><i class="fa fa-exchange" style="cursor: pointer;color: green;"></i></span>
            @endif
        </h2>
    </div>

    <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <br/>
            @for($i=1;$i<=6;$i++)

                @if(isset($export_data["manager_kv_{$i}"]) && getFloatFormat($export_data["manager_kv_{$i}"]) > 0)
                    <div class="view-field">
                        <span class="view-label">{{$export_data["manager_name_{$i}"]}}</span>
                        <span class="view-value">{{titleFloatFormat($export_data["manager_kv_{$i}"])}}%</span>
                    </div>
                @endif

                @if(isset($export_data["agent_name_{$i}"]) && getFloatFormat($export_data["agent_kv_{$i}"]) > 0)
                    <div class="view-field">
                        <span class="view-label">{{$export_data["agent_name_{$i}"]}}</span>
                        <span class="view-value">{{titleFloatFormat($export_data["agent_kv_{$i}"])}}%</span>
                    </div>
                @endif

            @endfor

            <div class="clear"></div>
        </div>


    </div>

@else


<div class="page-heading">
    <h2 class="inline-h1">Агенты
        @if($contract->statys_id == 4 && $contract->data->type_agr_id == 1)
            <span class=" pull-right" data-intro='Пролонгировать договор!' onclick="prolongationContract('{{$contract->id}}')"><i class="fa fa-exchange" style="cursor: pointer;color: green;"></i></span>
        @endif
    </h2>
</div>

<div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <br/>
        @if($contract->data->agent)
            <div class="view-field">
                <span class="view-label">{{$contract->data->agent->name}}</span>
                <span class="view-value">{{titleFloatFormat($contract->data->kv_agent)}}%</span>
            </div>
        @endif

        @if($contract->data->agent2)
            <div class="view-field">
                <span class="view-label">{{$contract->data->agent2->name}}</span>
                <span class="view-value">{{titleFloatFormat($contract->data->kv_agent2)}}%</span>
            </div>
        @endif


        <div class="clear"></div>
    </div>


</div>

<div class="page-heading">
    <h2 class="inline-h1">Менеджеры</h2>
</div>

<div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <br/>
        @if($contract->data->manager)
            <div class="view-field">
                <span class="view-label">{{$contract->data->manager->name}}</span>
                <span class="view-value">{{titleFloatFormat($contract->data->kv_manager)}}%</span>
            </div>
        @endif

        @if($contract->data->manager2)
            <div class="view-field">
                <span class="view-label">{{$contract->data->manager2->name}}</span>
                <span class="view-value">{{titleFloatFormat($contract->data->kv_manager2)}}%</span>
            </div>
        @endif

        @if($contract->data->manager3)
            <div class="view-field">
                <span class="view-label">{{$contract->data->manager3->name}}</span>
                <span class="view-value">{{titleFloatFormat($contract->data->kv_manager3)}}%</span>
            </div>
        @endif


        <div class="clear"></div>
    </div>


</div>

@endif