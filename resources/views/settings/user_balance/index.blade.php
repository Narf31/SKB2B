@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.user_balance') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/user_balance/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($user_balance))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
                    <th><a href="#">Тип</a></th>
                </tr>
            </thead>
            @foreach($user_balance as $balance)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/user_balance/$balance->id/edit") }}')">
                    <td>{{ $balance->title }}</td>
                    <td>{{ ($balance->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                    <td>{{ \App\Models\Settings\UserBalanceSettings::TYPE[$balance->type_id]  }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
