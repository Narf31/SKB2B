<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        График платежей
    </div>
    <div class="calc__menu">
        <ul>

            @if(sizeof($payments))
                @foreach($payments as $payment)


                    <li>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                            <div class="form__field" style="margin-top: 5px;">
                                <div class="checkbox__title">{{\App\Models\Contracts\Payments::STATUS[$payment->statys_id]}}</div>
                                <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                    {{setDateTimeFormatRu($payment->payment_data, 1)}} - {{titleFloatFormat($payment->payment_total)}} руб.
                                </div>

                            </div>
                        </div>
                    </li>

                @endforeach
            @endif


        </ul>
    </div>
</div>

<div class="clear"></div>


@if($payment_link)

    <a class="btn__round d-flex align-items-center justify-content-center" href="{{urlClient("/contracts/online/payment-link/{$contract->md5_token}")}}">Оплатить</a>

@endif

<div class="clear"></div>
