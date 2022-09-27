@extends('layouts.frame')

@section('title')

    Алгоритм рассрочки

    @if($algorithm->id>0)
        <span class="btn btn-info pull-right" onclick="openLogEvents('{{$algorithm->id}}', 12, 0)"><i class="fa fa-history"></i> </span>
    @endif
@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/installment_algorithms/".((int)$algorithm->id)."/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<div class="form-group">
    <label class="col-sm-4 control-label">Алгоритм</label>
    <div class="col-sm-8">
        {{ Form::select('algorithm_id', collect(\App\Models\Directories\InstallmentAlgorithms::ALG_TYPE), $algorithm->algorithm_id, ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>


{{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop