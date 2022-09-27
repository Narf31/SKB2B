@extends('layouts.app')

@section('content')

<div class="page-heading">
    <h1 class="inline-h1">{{ trans('menu.insurance_companies') }}</h1>
    <a class="btn btn-primary btn-right" href="{{ url("/directories/insurance_companies/0/")  }}">
        {{ trans('form.buttons.create') }}
    </a>
</div>

@if(sizeof($insurance_companies))
<table class="tov-table">
    <thead>
        <tr>
            <th><a href="#">{{ trans('settings/banks.title') }}</a></th>
            <th><a href="#">{{ trans('settings/banks.is_actual') }}</a></th>
            <th>Логотип</th>
            <th></th>
        </tr>
    </thead>

    @foreach($insurance_companies as $sk)
    <tr class="clickabe-tr" onclick="location.href = '{{url ("/directories/insurance_companies/$sk->id/")}}';">
        <td>{{ $sk->title }}</td>
        <td>{{ ($sk->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
        <td class="text-center">
            @if($sk->logo_id)
            <img src="{{ url($sk->logo->url) }}" width="154" height="44">
            @endif
        </td>
        <td>
            <span class="btn btn-primary btn-right">
                Открыть
            </span>
        </td>
    </tr>
    @endforeach
</table>
@else
{{ trans('form.empty') }}
@endif





@endsection

