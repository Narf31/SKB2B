@extends('settings.notifications.layout')

@section('notification')
     БСО {{ $log->bso->bso_title }} передано агенту {{ $log->user_to ? $log->user_to->name : "" }}
@endsection



