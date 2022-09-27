

@if(auth()->user()->hasPermission('directories', 'organizations_edit'))
{{ Form::model($organization, ['url' => url($send_urls), 'method' => 'put',  'class' => 'form-horizontal']) }}
@endif

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">



                <div class="form-group">
                    <label class="col-sm-4 control-label">Платежный шлюз</label>
                    <div class="col-sm-8">
                        {{ Form::select('payment_type_agent', collect(\App\Services\PaymentAgent\IntegrationPaymentAgent::getPaymentAgentList()),  $organization->payment_type_agent,  ['class' => 'form-control select2-all']) }}
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-4 control-label">Login/api Key</label>
                    <div class="col-sm-8">
                        {{ Form::text('api_key', $organization->api_key, ['class' => 'form-control', '']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Password/secret Key</label>
                    <div class="col-sm-8">
                        {{ Form::text('secret_key', $organization->secret_key, ['class' => 'form-control', '']) }}
                    </div>
                </div>




                @if(auth()->user()->hasPermission('directories', 'organizations_edit'))
                <div class="form-group">
                    <div class="col-sm-12">

                        <button type="submit" class="btn btn-primary pull-right">
                            Сохранить
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>



{{Form::close()}}

<script>

    $(function(){

    });

    function initTab() {
        startMainFunctions();

    }



</script>