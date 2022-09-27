@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.citys') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/citys/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($citys))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="javascript:void(0);">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="javascript:void(0);">КЛАДР</a></th>
                    <th><a href="javascript:void(0);">{{ trans('settings/banks.is_actual') }}</a></th>
                </tr>
            </thead>
            @foreach($citys as $city)
                <tr onclick="openFancyBoxFrame('{{url ("/settings/citys/$city->id/edit")}}')">
                    <td>{{ $city->title }}</td>
                    <td>{{ $city->kladr }}</td>
                    <td>{{ ($city->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif





@endsection

