@extends('settings.notifications.layout')

@section('notification')
    Пользователь {{ $log->user ? $log->user->name : "" }} отправил
    <a href="/contracts/temp_contracts/contract/{{$log->contract_id}}/edit">договор</a>
    на проверку
@endsection



