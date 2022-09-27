@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.country') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/country/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($country))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">Название</a></th>
                    <th><a href="#">RU</a></th>
                    <th><a href="#">EN</a></th>
                    <th><a href="#">ISO-код страны</a></th>
                    <th><a href="#">Шенген</a></th>
                </tr>
            </thead>
            @foreach($country as $coun)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/country/$coun->id/edit") }}')">
                    <td>{{ $coun->title }}</td>
                    <td>{{ $coun->title_ru }}</td>
                    <td>{{ $coun->title_en }}</td>
                    <td>{{ $coun->code }}</td>
                    <td>{{ ($coun->is_schengen == 1)?"Да":"Нет" }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
