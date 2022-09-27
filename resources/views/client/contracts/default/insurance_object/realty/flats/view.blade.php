<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        Территория страхования
    </div>
    <div class="calc__menu">
        <ul>

            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">{{$object->address}}</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            &nbsp;
                        </div>

                    </div>
                </div>
            </li>

            @foreach($contract->product->flats_risks as $flats_risks)

                @if(array_search("$flats_risks->id", $terms) !== false)

                    <li>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                            <div class="form__field" style="margin-top: 5px;">
                                <div class="checkbox__title">{{$flats_risks->title}}</div>
                                <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                    {{titleFloatFormat($flats_risks->insurance_amount)}} руб.
                                </div>

                            </div>
                        </div>
                    </li>

                @endif
            @endforeach

        </ul>
    </div>
</div>

<div class="clear"></div>



