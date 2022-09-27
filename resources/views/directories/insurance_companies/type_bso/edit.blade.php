@extends('layouts.frame')

@section('title')

    Типы и серии БСО

    @if($type_bso->id>0)
        <span class="btn btn-info pull-right" onclick="openLogEvents('{{$type_bso->id}}', 13, 1)"><i class="fa fa-history"></i> </span>
    @endif

@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/".((int)$type_bso->id)."/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, $type_bso->is_actual) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Продукт</label>
    <div class="col-sm-8">
        {{ Form::select('product_id', \App\Models\Directories\Products::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('Иной', 0), $type_bso->product_id, ['class' => 'form-control select2-ws', 'id'=>'product_id', 'onchange'=>'setBsoTypeName()','required']) }}
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-4 control-label">Название</label>
    <div class="col-sm-8">
        {{ Form::text('title', $type_bso->title, ['class' => 'form-control', 'id'=>'title']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Мин. желтый</label>
    <div class="col-sm-8">
        {{ Form::text('min_yellow', $type_bso->min_yellow, ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Мин. красный</label>
    <div class="col-sm-8">
        {{ Form::text('min_red', $type_bso->min_red, ['class' => 'form-control', 'required']) }}
    </div>
</div>


<div class="form-group">
    <label class="col-sm-4 control-label">Дней у агента</label>
    <div class="col-sm-8">
        {{ Form::text('day_agent', $type_bso->day_agent, ['class' => 'form-control', 'required']) }}
    </div>
</div>

{{Form::close()}}

@if($type_bso->id >0 )
<div class="form-group">

    <div class="page-heading">
        <h1 class="inline-h1">Серия БСО</h1>
        <a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{((int)$type_bso->id)}}/bso_serie/0/"
           class="btn btn-primary btn-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>

    @if(sizeof($type_bso->bso_serie))
        <table class="tov-table">
            <tbody>

                @foreach($type_bso->bso_serie as $bso)
                    <tr class="clickable-row">
                        <td><a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{((int)$type_bso->id)}}/bso_serie/{{$bso->id}}/"> {{ $bso->bso_serie  }}</a></td>
                        <td><a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{((int)$type_bso->id)}}/bso_serie/{{$bso->id}}/"> {{ \App\Models\Directories\TypeBso::CLASS_BSO[$bso->bso_class_id]  }}</a></td>
                        <td>{{($bso->is_actual == 1)?"Актуально":"Нет"}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

</div>
@endif

@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

@section('js')

    <script>


        function setBsoTypeName(){
            if(parseInt($("#product_id").val())>0){
                $("#title").val($("#product_id option:selected").text());
            }
        }


    </script>

@stop