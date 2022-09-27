@extends('layouts.frame')


@section('title')
    Добавление к счету
@stop

@section('content')
    {{ Form::open(['url' => url('/finance/invoice/payments/update_invoice/'), 'method' => 'post', 'class' => 'form-horizontal']) }}
        
    @if(sizeof($payments))
        @foreach($payments as $payment)
            <input type="hidden" name="payments[]" value="{{$payment->id}}">
        @endforeach
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label">Номер платежа</label>
        <div class="col-sm-8">
            {{ Form::select('invoice_id', $select, 1, ['class' => 'form-control select2 select2-all', 'id'=>'invoice_id', 'required']) }}
        </div>
    </div>
    
    
    @if(sizeof($messages))
        @foreach($messages as $msg)
        
        @if($msg['type'] == 'error')
         <div class="alert alert-danger" role="alert">
            <p>{{$msg['name']}}</p>
         </div>
        @elseif($msg['type'] == 'success')
         <div class="alert alert-success" role="alert">
            <p>{{$msg['name']}}</p>
         </div>
        @else
        <div class="alert alert-light" role="alert">
         <p>{{$msg['name']}}</p>
        </div>
        @endif
        
        @endforeach
    @endif
    

    {{Form::close()}}

@stop

@section('footer')
    <div class="row">
        <div class="col-12">
            <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.create') }}</button>
        </div>
    </div>
@stop


@section('js')

@endsection