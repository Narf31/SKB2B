@extends('client.layouts.app')

@section('head')

@append

@section('content')

    <div class="section sc__common">
        <div class="container">
            <div class="section__box">
                <div class="sc__title seo__item">
                    Договоры
                </div>
                <div class="why__list">
                    <div class="row row__custom">
                        @foreach($contracts as $key => $contract)

                            <div class="col-xs-12 col-sm-7 col-md-6 col-lg-4">
                                <a href="{{urlClient("/contracts/online/{$contract->md5_token}")}}" class="order-item">
                                    <div class="order-title">
                                        <span class="order-number">{{$contract->bso_title}}</span>
                                        <span class="status circle @if($contract->getActualStatus() =='Действует') green @else red @endif">{{$contract->getActualStatus()}}</span>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="order-contacts">
                                        <div class="title">Продукт</div>
                                        <div class="name">{{$contract->product->title}}</div>
                                        <div class="title">Даты действия</div>
                                        <div class="name">{{setDateTimeFormatRu($contract->begin_date, 1)}} - {{setDateTimeFormatRu($contract->end_date, 1)}}</div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="order-contacts">
                                        <div class="title">График платежей </div>

                                        @if(sizeof($contract->payments))
                                            @foreach($contract->payments as $payment)


                                                <div class="name">
                                                    {{\App\Models\Contracts\Payments::STATUS[$payment->statys_id]}}
                                                    <span class="pull-right">
                                                        {{setDateTimeFormatRu($payment->payment_data, 1)}} - {{titleFloatFormat($payment->payment_total)}} руб.
                                                    </span>

                                                </div>


                                            @endforeach
                                        @endif


                                    </div>
                                    <div class="divider"></div>
                                    <div class="order-summary">
                                        <div class="discount">
                                            <br/> <br/>
                                        </div>
                                        <div class="total">
                                            <div class="title">Страховая премия</div>
                                            <span class="value pull-right">{{titleFloatFormat($contract->payment_total)}}</span>
                                        </div>

                                    </div>
                                </a>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>


            <div class="section__box">
                <div class="sc__title seo__item">
                    Продукты страхования
                </div>
                <div class="why__list">
                    <div class="row row__custom">


                        @foreach($products as $key => $product)


                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 col__custom why__item">

                                <div class="why__item-icon d-flex align-items-center justify-content-center">
                                    <img src="/assets/client/img/why-{{$key+1}}.svg">
                                </div>
                                <div class="why__item-title">
                                    <a href="{{urlClient("/contracts/online/{$product->id}/create/")}}" class="btn__custom d-flex align-items-center justify-content-center">

                                    {{$product->title}}
                                    </a>
                                </div>


                            </div>


                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


