@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.filials') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/filials/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($filials))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">{{ trans('settings/departments.title') }}</a></th>
                    <th><a href="#">Город</a></th>
                </tr>
            </thead>
            @foreach($filials as $filial)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/filials/$filial->id/edit")  }}')">
                    <td>{{ $filial->title }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection






