@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('settings/financial_policy.index.page_title') }}</div>

                    <div class="panel-body">
                        <div class="col-md-12">

                            {{ Form::model($financialPolicy, ['url' => url("/settings/financial_policy/$financialPolicy->id"), 'method' => 'patch']) }}

                            @include('settings.financial_policy.form')

                            {{Form::close()}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
