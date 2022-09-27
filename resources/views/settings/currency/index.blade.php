@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.currency') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/currency/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($currency))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">Название</a></th>
                    <th><a href="#">Код</a></th>
                </tr>
            </thead>
            @foreach($currency as $curr)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/currency/$curr->id/edit") }}')">
                    <td>{{ $curr->title }}</td>
                    <td>{{ $curr->code }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
