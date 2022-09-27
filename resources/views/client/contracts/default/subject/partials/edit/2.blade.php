<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        Застрахованный
    </div>
    <div class="calc__menu">
        <ul>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">ФИО</div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{$subject->fio}}
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
</div>



<div class="row form-horizontal">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">ФИО</span>
            <span class="view-value">{{$subject->fio}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Пол</span>
            <span class="view-value">@if($subject->sex = 0)
                    <option value="0"></option>
                @else
                    <option value="1">жен.</option>
                @endif</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Дата рождения</span>
            <span class="view-value">{{getDateFormatRu($subject->birthdate)}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Телефон</span>
            <span class="view-value"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Email</span>
            <span class="view-value"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Место рождения</span>
            <span class="view-value">{{$subject->address_born}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Адрес регистрации</span>
            <span class="view-value">{{$subject->address_born}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Адрес фактический</span>
            <span class="view-value">{{$subject->address_fact}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Тип документа</span>
            <span class="view-value">Паспорт гражданина РФ</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Серия </span>
            <span class="view-value">{{$subject->doc_serie}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Номер </span>
            <span class="view-value">{{$subject->doc_number}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Дата выдачи </span>
            <span class="view-value">{{setDateTimeFormatRu($subject->doc_date, 1)}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Код подразделения </span>
            <span class="view-value">{{$subject->doc_office}}</span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Кем выдан </span>
            <span class="view-value">{{$subject->doc_info}}</span>
        </div>
    </div>

</div>