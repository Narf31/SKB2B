<div class="calc__menu">
    <ul>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">ФИО</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->fio}} ({{($subject->sex == 0)?'муж.':'жен.'}})
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Дата рождения</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{getDateFormatRu($subject->birthdate)}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">{{\App\Models\Contracts\Subjects::DOC_TYPE[$subject->doc_type]}}</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->doc_serie}} {{$subject->doc_number}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Дата выдачи</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{setDateTimeFormatRu($subject->doc_date, 1)}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Код подразделения</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->doc_office}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Кем выдан</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->doc_info}}
                    </div>

                </div>
            </div>
        </li>

        <li>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                <div class="form__field" style="margin-top: 5px;">
                    <div class="checkbox__title">Адрес</div>
                    <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                        {{$subject->address_register}}
                    </div>

                </div>
            </div>
        </li>

    </ul>
</div>