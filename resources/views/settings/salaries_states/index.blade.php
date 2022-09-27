@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.salaries_states') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/salaries_states/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>


    @if(sizeof($states))
        <table class="tov-table">
           <thead>
               <tr>
                   <th><a href="#">{{ trans('settings/salaries_states.title') }}</a></th>
                   <th><a href="#">{{ trans('settings/salaries_states.prefix') }}</a></th>
               </tr>
           </thead>

            @foreach($states as $state)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/salaries_states/$state->id/edit") }}')">
                    <td>{{ $state->title }}</td>
                    <td>{{ $state->prefix }}</td>
                </tr>
            @endforeach
        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection

