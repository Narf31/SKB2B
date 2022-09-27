@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.roles') }}</h1>
        <a class="btn btn-primary btn-right" href="{{ url('/users/roles/create')  }}">
            {{ trans('form.buttons.create') }}
        </a>
    </div>

    @if(sizeof($roles))
        <table class="tov-table">
            <tbody>
                <tr class="sort-row">
                    <th>{{ trans('users/roles.index.title') }}</th>
                </tr>
                @foreach($roles as $role)
                    <tr class="clickable-row" data-href="{{url ("/users/roles/$role->id/edit")}}">
                        <td width="70%">{{ $role->title }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{ trans('form.empty') }}
    @endif


@endsection

