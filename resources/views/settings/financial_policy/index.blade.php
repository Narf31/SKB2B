@extends('layouts.app')

@section('content')




    <div class="page-heading">
        <h1>{{ trans('settings/financial_policy.index.page_title') }}</h1>
        <a class="btn btn-primary"  onclick="openFancyBoxFrame('{{ url('/settings/financial_policy/create')  }}')">
            {{ trans('form.buttons.create') }}
        </a>
    </div>


    <div class="block-main">
    @if(sizeof($financialPolicies))
        <table class="noScrollTable">
            <thead>
                <tr>
                    <th>{{ trans('settings/financial_policy.index.id') }}</th>
                    <th>{{ trans('settings/financial_policy.index.title') }}</th>
                    <th>{{ trans('settings/financial_policy.index.is_active') }}</th>
                    <th>{{ trans('settings/financial_policy.index.types_trailers_title') }}</th>
                    <th>{{ trans('settings/financial_policy.index.kv_km') }}</th>
                </tr>
            </thead>

            @foreach($financialPolicies as $financialPolicy)
                <tr data-href="{{url ("/settings/financial_policy/$financialPolicy->id/edit")}}">
                    <td>{{ $financialPolicy->id  }}</td>
                    <td>{{ $financialPolicy->title }}</td>
                    <td>{{ ($financialPolicy->is_active==1)?trans('settings/financial_policy.index.is_active_yes'):trans('settings/financial_policy.index.is_active_no') }}</td>
                    <td>{{ $financialPolicy->types_trailers_title }}</td>
                    <td>{{ $financialPolicy->kv_km }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('settings/financial_policy.index.list_not_financial_policy') }}
    @endif

    </div>


@endsection
