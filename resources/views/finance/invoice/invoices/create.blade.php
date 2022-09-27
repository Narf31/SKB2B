@extends('layouts.frame')


@section('title')
    Создание счёта
@stop

@section('content')

    {{ Form::open(['url' => url('/finance/invoice/invoices/create'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @if(sizeof($payments))
        @foreach($payments as $payment)
            <input type="hidden" name="payments[]" value="{{$payment->id}}">
        @endforeach
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label">Тип операции</label>
        <div class="col-sm-8">
            @php($type_select = collect(\App\Models\Finance\Invoice::CREATE_TYPES))
            {{ Form::select('create_type', $type_select, 1, ['class' => 'form-control select2 select2-all', 'id'=>'create_type', 'required']) }}
        </div>
    </div>



    <div class="form-group" style="display: none" id="jure_form_group">
        <label class="col-sm-4 control-label">Юр. лицо</label>
        <div class="col-sm-8">
            {{ Form::select('org_id', $organizations->pluck('title', 'id'), 1, ['class' => 'form-control select2 select2-all', 'id'=>'org_id', 'required']) }}
        </div>
    </div>

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
    <script>
        $(function(){

           $(document).on('change', '[name="create_type"]', function(){
               if($(this).val()*1 === 2){
                   $('#jure_form_group').show();
                   $(window.parent.document.getElementsByClassName('fancybox-inner')).css({'min-height': '322px'});
               }else{
                   $('#jure_form_group').hide();
                   $(window.parent.document.getElementsByClassName('fancybox-inner')).css({'min-height': '286px'});
               }
           })
        });


    </script>

@endsection