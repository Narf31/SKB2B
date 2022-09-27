@extends('layouts.app')

@section('content')



    {{ Form::open(['url' => url('/directories/insurance_companies/'.(int)$insurance_companies->id.'/'), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract', 'files' => true]) }}
    <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Основная информация</h2>
            @if($insurance_companies->id>0)
                <span class="btn btn-info pull-right" onclick="openLogEvents('{{$insurance_companies->id}}', 11, 2)"><i class="fa fa-history"></i> </span>
            @endif
        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Актуально</label>
                        <div class="col-sm-8">
                             {{ Form::checkbox('is_actual', 1, $insurance_companies->is_actual) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Название</label>
                        <div class="col-sm-8">
                            {{ Form::text('title', $insurance_companies->title, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Логотип</label>
                        <div class="col-sm-8">
                            <input type="file" name="logo"/>
                            @if(isset($insurance_companies) && $insurance_companies->logo_id > 0)
                                <img src="{{ url($insurance_companies->logo->url) }}" width="154" height="44">
                            @endif
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-sm-12">
                            <input class="pull-right btn btn-primary" type="submit" value="Сохранить"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}


    @if(isset($insurance_companies) && $insurance_companies->id > 0)

        @include('directories.insurance_companies.partials.type_bso', ['insurance_companies' => $insurance_companies])

        <div class="row"></div>
        <br><br><br><br>

        @include('directories.insurance_companies.partials.suppliers_bso', ['insurance_companies' => $insurance_companies])

    @endif









@endsection

