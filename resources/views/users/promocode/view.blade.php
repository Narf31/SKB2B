@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Промокод - {{$user->name}}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url("/users/promocode/create?user={$user->id}")  }}')">
           Создать
        </span>
    </div>


    <table class="tov-table">
        <thead>
        <tr>
            <th><a href="#">#</a></th>
            <th><a href="#">Код</a></th>
            <th><a href="#">Дата действия</a></th>
            <th><a href="#">Актуален</a></th>
            <th><a href="#">Договор</a></th>
            <th><a href="#">Страхователь</a></th>
        </tr>
        </thead>
        @if(sizeof($codes))
            @foreach($codes as $key => $code)
                <tr @if($code->contract) class="bg-green" @endif>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $code->title }}</td>
                    <td>{{ setDateTimeFormatRu($code->valid_date, 1) }}</td>
                    <td>{{ ($code->is_actual==1)? 'Да' : 'Нет' }}</td>
                    <td>
                        @if($code->contract)
                            <a target="_blank" href="{{url("/contracts/online/{$code->contract->id}")}}">{{$code->contract->bso->bso_title}}</a>
                        @endif
                    </td>
                    <td>{{ $code->contract ? $code->contract->insurer->title : '' }}</td>
                </tr>
            @endforeach
        @endif
    </table>





@endsection

@section('js')



    <script>

        $(function () {


        });

    </script>
@endsection
