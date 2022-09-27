@extends('layouts.frame')

@section('title')

    {{ trans('menu.incomes_expenses_categories') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$income_expense_category->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($income_expense_category, ['url' => url("/settings/incomes_expenses_categories/$income_expense_category->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.incomes_expenses_categories.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/incomes_expenses_categories/', '{{ $income_expense_category->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

