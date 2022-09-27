@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.type_org') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/type_org/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($type_orgs))
        <table class="tov-table">
            <thead>
                <tr>
                    <th>
                        <a href="#">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
                    <th><a href="#">Поставщик БСО</a></th>
                    <th><a href="#">Участник договора</a></th>
                </tr>
            </thead>

            @foreach($type_orgs as $type_org)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/type_org/$type_org->id/edit") }}')">
                    <td>{{ $type_org->title }}</td>
                    <td>{{ ($type_org->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                    <td>{{ ($type_org->is_provider==1)? trans('form.yes') :trans('form.no') }}</td>
                    <td>{{ ($type_org->is_contract==1)? trans('form.yes') :trans('form.no') }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
