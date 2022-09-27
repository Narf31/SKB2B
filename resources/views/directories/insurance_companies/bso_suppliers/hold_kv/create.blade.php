@extends('layouts.app')



@section('content')
    <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Настройка продуктов <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}")}}">{{$bso_supplier->title}}</a></h2>
        </div>
        <div class="block-main">
            <div class="block-sub">

                {{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv"), 'method' => 'post', 'class' => 'form-horizontal']) }}

                @include('directories.insurance_companies.bso_suppliers.hold_kv.form')

                <input type="submit" class="btn btn-primary" value="Сохранить"/>

                {{Form::close()}}

            </div>
        </div>
    </div>
@stop
