@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Алгоритмы рассрочки</h1>
        <a class="btn btn-primary btn-right" href="{{ url('/settings/installment_algorithms_payment/0/edit')  }}">
            {{ trans('form.buttons.create') }}
        </a>
    </div>

    @if(sizeof($algorithms))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="#">Кол-во платежей</a></th>
                    <th><a href="#">Название</a></th>
                    <th><a href="#">Детали</a></th>
                </tr>
            </thead>
            @foreach($algorithms as $algorithm)
                <tr @if($algorithm->is_default == 1) class="bg-green odd" @endif onclick="openPage('{{ url("/settings/installment_algorithms_payment/$algorithm->id/edit") }}')">
                    <td>{{ $algorithm->quantity }}</td>
                    <td>{{ $algorithm->title }}</td>
                    <td>{{ $algorithm->details_quantity }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
