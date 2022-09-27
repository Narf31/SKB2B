<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">

        <span>Заявка #{{$damage->id}} - {{\App\Models\Orders\Damages::STATYS[$damage->status_id]}}</span>


    </div>
    <div class="calc__menu">
        <ul>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Город</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$damage->city->title}}
                        </div>

                    </div>
                </div>
            </li>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Тип</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{ \App\Models\Orders\Damages::POSITION_TYPE[$damage->position_type_id]}}
                        </div>

                    </div>
                </div>
            </li>



            @if($damage->position_type_id == 1)

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Точка осмотра</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{($damage->point_sale)?$damage->point_sale->title:''}}
                            </div>

                        </div>
                    </div>
                </li>

            @endif

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Дата / Время</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{setDateTimeFormatRu($damage->begin_date)}}
                        </div>

                    </div>
                </div>
            </li>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Адрес осмотра</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$damage->address}}
                        </div>

                    </div>
                </div>
            </li>


            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Телефон</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$damage->phone}}
                        </div>

                    </div>
                </div>
            </li>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Email</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$damage->email}}
                        </div>

                    </div>
                </div>
            </li>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Сумма убытка ({{\App\Models\Orders\DamageOrder::STATUS_PAYMENT[($damage->info)?$damage->info->status_payments_id:0]}})</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{titleFloatFormat(($damage->info)?$damage->info->payments_total:0)}}
                        </div>

                    </div>
                </div>
            </li>



        </ul>
    </div>
</div>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
    {{$damage->comments}}
</div>
