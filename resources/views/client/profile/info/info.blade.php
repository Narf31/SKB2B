<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        {{ $client->title }}
    </div>
    <div class="calc__menu">
        <ul>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">ФИО</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$client->title}} ({{($client->sex == 0)?'муж.':'жен.'}})
                        </div>

                    </div>
                </div>
            </li>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Дата рождения</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{getDateFormatRu($client->birthdate)}}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Email</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$client->email}}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">Телефон</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{strlen($client->phone)>8?$client->phone:'Отсутствует'}}
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>