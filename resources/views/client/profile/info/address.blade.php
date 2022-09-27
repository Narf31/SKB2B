<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        Адреса
    </div>
    <div class="calc__menu">
        <ul>

            @if(sizeof($client->address))
                @foreach($client->address as $addres)

                    <li>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                            <div class="form__field" style="margin-top: 5px;">
                                <div class="checkbox__title">{{\App\Models\Clients\GeneralSubjectsAddress::TYPE_NAME[$addres->type_id]}}</div>
                                <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                    {{$addres->address}}
                                </div>

                            </div>
                        </div>
                    </li>

                @endforeach
            @endif


        </ul>
    </div>
</div>