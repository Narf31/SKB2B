@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.financial_policy') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/financial_policy/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($financial_groups))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
                </tr>
            </thead>
            @foreach($financial_groups as $financial_group)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/financial_policy/$financial_group->id/edit") }}')">
                    <td>{{ $financial_group->title }}</td>
                    <td>{{ ($financial_group->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection

