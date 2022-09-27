
<div class="calc__menu">
    <ul>
        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Название компании</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->title}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">ИНН / КПП</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->inn}} / {{$subject->kpp}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">БИК</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->bik}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Генеральный директор</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->general_manager}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Адрес регистрации</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->address_register}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Адрес фактический</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->address_fact}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Контактное лицо</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->fio}} - {{$subject->position}}
                    </div>

                </div>
            </div>
        </li>


    </ul>
</div>