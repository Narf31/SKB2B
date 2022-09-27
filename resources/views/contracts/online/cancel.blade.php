@extends('layouts.frame')

@section('title')
    Анулирование договора {{$contract->bso->bso_title}}
@stop

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 300px;" >
        <div class="row form-horizontal">
            {{ Form::open(['url' => url("/contracts/online/{$contract->id}/cancel"), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract']) }}

            <h2>Укажите статус</h2>

            <div class="form-group">
                <label class="col-sm-12 control-label" style="font-weight: bold;">БСО - {{$contract->bso->bso_title}}</label>
                <div class="col-sm-12">
                    {{ Form::select("bso_states[{$contract->bso->id}]", collect([0=>'Чистый', 3=>'Испорчен']), ($contract->bso->state_id == 0?0:3), ['class' => 'form-control']) }}
                </div>
            </div>

            @foreach($contract->payments as $payment)
                @if($payment->bso_receipt_id > 0)
                    <div class="form-group">
                        <label class="col-sm-12 control-label" style="font-weight: bold;">Квитанция - {{$payment->receipt->bso_title}}</label>
                        <div class="col-sm-12">
                            {{ Form::select("bso_states[{$payment->receipt->id}]", collect([0=>'Чистый', 3=>'Испорчен']), ($contract->receipt->state_id == 0?0:3), ['class' => 'form-control']) }}
                        </div>
                    </div>
                @endif
            @endforeach

            {{Form::close()}}
        </div>
    </div>

@stop

@section('footer')

<span class="btn btn-success pull-right" id="butt_accept" onclick="submitForm()">Анулировать</span>
<br/><br/>
@stop

@section('js')
<script>



</script>

@stop