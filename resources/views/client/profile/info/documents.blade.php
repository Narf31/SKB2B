<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row row__custom justify-content-between">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12 col__custom">
            @if(sizeof($client->documents))
                @foreach($client->documents as $document)
                    <div class="content__box" style="width: 100%;padding-bottom: 20px;">
                        <div class="content__box-title seo__item">
                            {{\App\Models\Clients\GeneralSubjectsDocuments::TYPE[$document->type_id]}}
                        </div>
                        <div class="calc__menu">
                            <ul>
                                <li>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                                        <div class="form__field" style="margin-top: 5px;">
                                            <div class="checkbox__title">Серия / Номер</div>
                                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                                {{$document->serie}} {{$document->number}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                                        <div class="form__field" style="margin-top: 5px;">
                                            <div class="checkbox__title">Дата выдачи</div>
                                            <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                                                {{setDateTimeFormatRu($document->date_issue, 1)}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>




