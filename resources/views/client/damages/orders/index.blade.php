
@if(sizeof($client->damages))
    @foreach($client->damages as $damage)
        <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
            <a href="{{urlClient("/damages/order/{$damage->id}")}}" class="order-item">
                <div class="order-title">
                    <span class="order-number">#{{$damage->id}}</span>
                    <span class="status circle {{getStatusColor($damage->status_id)}}">{{\App\Models\Orders\Damages::STATYS[$damage->status_id]}}</span>
                </div>
                <div class="divider"></div>
                <div class="order-contacts">
                    <div class="title">Договор</div>
                    <div class="name">{{$damage->bso->bso_title}}</div>
                    <div class="title">Продукт</div>
                    <div class="name">{{$damage->bso->product->title}}</div>
                    <div class="title">Тип</div>
                    <div class="name">{{ \App\Models\Orders\Damages::POSITION_TYPE[$damage->position_type_id]}}</div>

                    <div class="title">Дата / Время</div>
                    <div class="name">{{setDateTimeFormatRu($damage->begin_date)}}</div>

                    <div class="title">Адрес осмотра</div>
                    <div class="name">{{$damage->address}}</div>


                </div>

                <div class="divider"></div>
                <div class="order-summary">
                    <div class="discount">
                        <br/> <br/>
                    </div>
                    <div class="total">
                        <div class="title">Сумма убытка ({{\App\Models\Orders\DamageOrder::STATUS_PAYMENT[($damage->info)?$damage->info->status_payments_id:0]}})</div>
                        <span class="value pull-right">{{titleFloatFormat(($damage->info)?$damage->info->payments_total:0)}}</span>
                    </div>

                </div>
            </a>
        </div>
    @endforeach
@else

<div class="row text col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12">
    <br/><br/><br/><br/>
    <p style="font-size: 18px;">
        Нет заявок
    </p>
</div>

@endif

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12" >
    <a href="{{urlClient("/damages/create")}}" class="btn__round d-flex align-items-center justify-content-center">
        Создать заявку
    </a>
</div>

{{--
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    <a href="" class="order-item">
        <div class="order-title"><span class="order-number">11</span><span class="status circle purple">Выполнена</span></div>
        <div class="divider"></div>
        <div class="order-contacts">
            <div class="title">Договор</div>
            <div class="name">123123</div>
            <div class="title">Продукт</div>
            <div class="name">Получатель1</div>
            <div class="title">Комментарий</div>
            <div class="name"></div>
        </div>
        <div class="divider"></div>
        <div class="order-summary">
            <div class="discount">
                <br/> <br/>
            </div>
            <div class="total">
                <div class="title">Сумма</div>
                <span class="value">2 223 411,30</span>
            </div>

        </div>
    </a>
</div>
--}}