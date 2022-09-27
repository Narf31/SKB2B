
    <div class="content__box" style="width: 100%;padding-bottom: 20px;">
        <div class="content__box-title seo__item">

            Условия договора

        </div>
        <div class="calc__menu">
            <ul>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Договор</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{($contract->bso)?$contract->bso->bso_title:''}}
                            </div>

                        </div>
                    </div>
                </li>
                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Программа</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{$contract->getProductOrProgram()->title}}
                            </div>

                        </div>
                    </div>
                </li>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Дата заключения</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{setDateTimeFormatRu($contract->sign_date)}}
                            </div>

                        </div>
                    </div>
                </li>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Дата начала</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{setDateTimeFormatRu($contract->begin_date)}}
                            </div>

                        </div>
                    </div>
                </li>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Дата окончания</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{setDateTimeFormatRu($contract->end_date)}}
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Страховая сумма</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{titleFloatFormat($contract->insurance_amount)}} руб.
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                        <div class="form__field" style="margin-top: 5px;">
                            <div class="checkbox__title">Страховая премия</div>
                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                {{titleFloatFormat($contract->payment_total)}} руб.
                            </div>
                        </div>
                    </div>
                </li>


            </ul>
        </div>
    </div>




<script>

    function initTerms() {





    }



</script>