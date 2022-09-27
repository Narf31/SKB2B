{{ Form::open(['url' => url("/general/subjects/edit/{$general->id}/special"), 'method' => 'post', 'class' => 'row form-horizontal', 'id'=>'form-data']) }}

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="col-sm-12">
                        <h2>{{$general->title}}

                            @if($general->type_id == 0)
                                - {{setDateTimeFormatRu($general->data->birthdate, 1)}}
                            @endif
                        </h2>
                </div>


                @if($state == 'edit')

                    <div class="row col-sm-2">
                        <label class="col-sm-12 control-label">Уровень риска</label>
                        <div class="col-sm-12">
                            {{Form::select("risk_level_id", collect(\App\Models\Clients\GeneralSubjects::RISK_LEVEL), $general->risk_level_id, ['class' => 'form-control  select2-ws']) }}
                        </div>
                    </div>

                    <div class="row col-sm-3">
                        <label class="col-sm-12 control-label" style="max-width: none;">Кто присвоил уровень риска</label>
                        <div class="col-sm-12">
                            {{Form::select("risk_user_id", \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Нет', 0), $general->risk_user_id, ['class' => 'form-control  select2']) }}
                        </div>
                    </div>

                    <div class="row col-sm-2">
                        <label class="col-sm-12 control-label" style="max-width: none;">Дата присвоения уровня риска</label>
                        <div class="col-sm-12">
                            {{Form::text("risk_date", setDateTimeFormatRu($general->risk_date, 1), ['class' => 'form-control format-date']) }}
                        </div>
                    </div>

                    <div class="row col-sm-5">
                        <label class="col-sm-12 control-label" style="max-width: none;">История пересмотра уровня риска</label>
                        <div class="col-sm-12">
                            {{Form::text("risk_history", $general->risk_history, ['class' => 'form-control']) }}
                        </div>
                    </div>


                    <div class="clear"></div>

                    <div class="row col-sm-6">
                        <label class="col-sm-12 control-label" style="max-width: none;">Основание (уровеня риска)</label>
                        <div class="col-sm-12">
                            {{Form::textarea("risk_base", $general->risk_base, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="row col-sm-6">
                        <label class="col-sm-12 control-label" style="max-width: none;">Комментарий уполномоченного сотрудника</label>
                        <div class="col-sm-12">
                            {{Form::textarea("risk_comments", $general->risk_comments, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="row col-sm-12">
                        <div class="col-sm-12">
                            <span onclick="saveClients()" class="btn btn-primary pull-left">{{ trans('form.buttons.save') }}</span>
                        </div>
                    </div>

                @else

                    <div class="view-field">
                        <span class="view-label">Уровень риска</span>
                        <span class="view-value">{{\App\Models\Clients\GeneralSubjects::RISK_LEVEL[$general->risk_level_id]}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Кто присвоил уровень риска</span>
                        <span class="view-value">{{($general->risk_user)?$general->risk_user->name:''}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Дата присвоения уровня риска</span>
                        <span class="view-value">{{setDateTimeFormatRu($general->risk_date, 1)}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">История пересмотра уровня риска</span>
                        <span class="view-value">{{$general->risk_history}}</span>
                    </div>

                    <div class="row col-sm-6">
                        <label class="col-sm-12 control-label" style="max-width: none;">Основание (уровеня риска)</label>
                        <div class="col-sm-12">
                            {{$general->risk_base}}
                        </div>
                    </div>

                    <div class="row col-sm-6">
                        <label class="col-sm-12 control-label" style="max-width: none;">Комментарий уполномоченного сотрудника</label>
                        <div class="col-sm-12">
                            {{$general->risk_comments}}
                        </div>
                    </div>


                @endif




            </div>
        </div>
    </div>
</div>


{{Form::close()}}



<script>

    function saveClients()
    {

        if(validate()){



            loaderShow();

            $.post('{{url("/general/subjects/edit/{$general->id}/special")}}', $('#form-data').serialize(), function (response) {



                if (Boolean(response.state) === true) {

                    flashMessage('success', "Данные успешно сохранены!");

                }else {
                    if(response.errors){
                        $.each(response.errors, function (index, value) {
                            flashHeaderMessage(value, 'danger');
                            $('[name="' + index + '"]').addClass('form-error');
                        });
                    }else{
                        flashHeaderMessage(response.msg, 'danger');
                    }

                }

            }).always(function () {
                loaderHide();
            });

        }

    }


    function startMainFunctions() {

        initSelect2();
        initDataSubjects();
    }




</script>