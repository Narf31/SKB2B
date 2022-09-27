@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.points_sale') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/points_sale/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($points))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
                    <th><a href="#">Город</a></th>
                </tr>
            </thead>
            @foreach($points as $point)
                <tr onclick="openFancyBoxFrame('{{url ("/settings/points_sale/$point->id/edit")}}')">
                    <td>{{ $point->title }}</td>
                    <td>{{ ($point->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                    <td>{{ ($point->city)?$point->city->title:'' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection

