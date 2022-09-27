@extends('layouts.app')




@section('content')


    <div class="row">

        {{ Form::open(['url' => url("/finance/invoice/reservation/{$reservation->id}/edit"), 'method' => 'post', 'class' => 'form-horizontal', 'data-type' => 'edit']) }}

            @include('finance.invoice.reservation.form')

        {{Form::close()}}

    </div>





@stop
