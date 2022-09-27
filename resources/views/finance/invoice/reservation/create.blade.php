@extends('layouts.app')

@section('content')


    <div class="row">

        {{ Form::open(['url' => url('/finance/invoice/reservation/create'), 'method' => 'post',  'class' => 'form-horizontal', 'data-type' => 'create']) }}

        @include('finance.invoice.reservation.form')

        {{Form::close()}}
    </div>


@stop
