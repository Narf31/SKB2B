@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Промокод</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/users/promocode/create?user=0')  }}')">
           Создать
        </span>
    </div>


    <table class="tov-table">
        <thead>
        <tr>
            <th><a href="#">Пользователь</a></th>
            <th><a href="#">Куратор</a></th>
            <th><a href="#">Организация</a></th>
            <th><a href="#">Кол-во</a></th>
        </tr>
        </thead>
        @if(sizeof($users))
            @foreach($users as $user)
                <tr style="cursor: pointer;" onclick="openPage('{{url("/users/promocode/{$user->id}")}}')">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->curator ? $user->curator->name : '' }}</td>
                    <td>{{ $user->organization ? $user->organization->title : '' }}</td>
                    <td>{{ $user->promocode()->count('id') }} / {{ $user->promocode()->where('contract_id', '>', 0)->count('id') }}</td>
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
