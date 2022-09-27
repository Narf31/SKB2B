<div class="page-heading">
    <h2 class="inline-h1">Агенты</h2>
</div>

<div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-horizontal">

        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Агент</label>
            {{Form::select("contract[liability_arbitration_manager][kv_agent_id]", \App\Models\User::getALLUser()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->kv_agent_id, ['class' => 'form-control select2'])}}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КВ</label>
            {{ Form::text("contract[liability_arbitration_manager][kv_agent]", titleFloatFormat($contract->data->kv_agent), ['class' => 'form-control sum kv_sum']) }}
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Агент 2</label>
            {{Form::select("contract[liability_arbitration_manager][kv_agent2_id]", \App\Models\User::getALLUser()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->kv_agent2_id, ['class' => 'form-control select2'])}}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КВ</label>
            {{ Form::text("contract[liability_arbitration_manager][kv_agent2]", titleFloatFormat($contract->data->kv_agent2), ['class' => 'form-control sum kv_sum']) }}
        </div>


    </div>
</div>

<div class="page-heading">
    <h2 class="inline-h1">Менеджеры</h2>
</div>

<div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-horizontal">


        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Продавец</label>
            {{Form::select("contract[liability_arbitration_manager][kv_manager_id]", \App\Models\User::getALLUser()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->kv_manager_id, ['class' => 'form-control select2'])}}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КВ</label>
            {{ Form::text("contract[liability_arbitration_manager][kv_manager]", titleFloatFormat($contract->data->kv_manager), ['class' => 'form-control sum kv_sum']) }}
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Продавец 2</label>
            {{Form::select("contract[liability_arbitration_manager][kv_manager2_id]", \App\Models\User::getALLUser()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->kv_manager2_id, ['class' => 'form-control select2'])}}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КВ</label>
            {{ Form::text("contract[liability_arbitration_manager][kv_manager2]", titleFloatFormat($contract->data->kv_manager2), ['class' => 'form-control sum kv_sum']) }}
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Продавец 3</label>
            {{Form::select("contract[liability_arbitration_manager][kv_manager3_id]", \App\Models\User::getALLUser()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->kv_manager3_id, ['class' => 'form-control select2'])}}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КВ</label>
            {{ Form::text("contract[liability_arbitration_manager][kv_manager3]", titleFloatFormat($contract->data->kv_manager3), ['class' => 'form-control sum kv_sum']) }}
        </div>

        <div class="clear"></div>


    </div>
</div>


<script>


    function initTab() {
        
    }


</script>