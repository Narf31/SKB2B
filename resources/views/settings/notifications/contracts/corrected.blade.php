@extends('settings.notifications.layout')

@section('notification')
    Пользователь {{ $log->user ? $log->user->name : "" }} отправил
    <a href="/contracts/online/{{$log->contract_id}}">договор</a>
   {{$log->status_title}}
@endsection



