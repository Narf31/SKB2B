@extends('settings.notifications.layout')

@section('notification')
    Пользователь {{ $log->user ? $log->user->name : "" }} сдал
    <a href="/contracts/temp_contracts/contract/{{$log->contract_id}}/edit">договор</a>
    в кассу
@endsection