@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.banks') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/banks/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($banks))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
                </tr>
            </thead>
            @foreach($banks as $bank)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/banks/$bank->id/edit") }}')">
                    <td>{{ $bank->title }}</td>
                    <td>{{ ($bank->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
