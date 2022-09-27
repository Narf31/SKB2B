{{ Form::open(['url' => url("/general/subjects/edit/{$general->id}"), 'method' => 'post', 'class' => 'row form-horizontal', 'id'=>'form-data']) }}

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="col-sm-12">
                        <h2>{{$general->title}}

                            @if($general->type_id == 0)
                                - {{setDateTimeFormatRu($general->data->birthdate, 1)}}
                            @endif

                            @if($state == 'edit' && $general->type_id == 1 && $general->data && strlen($general->data->inn) > 0 && strlen($general->data->ogrn) > 0)

                                {{--<span class="btn btn-info pull-right" onclick="updateInfo()">Контур.Призма</span>--}}

                            @endif

                        </h2>



                </div>


                @if($general->type_id == 0)
                    @include("general.subjects.info.fl.data.{$state}")
                @else
                    @include("general.subjects.info.ul.data.{$state}")
                @endif

                @if($state == 'edit')
                    <div class="row col-sm-12">
                        <div class="col-sm-12">
                            <span onclick="saveClients()" class="btn btn-primary pull-left">{{ trans('form.buttons.save') }}</span>
                        </div>
                    </div>
                @endif




            </div>
        </div>
    </div>
</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">


                @if($state == 'edit')

                @if($general->type_id == 0)
                    <div class="row col-sm-12">
                        <label class="col-sm-12 control-label">Профессия</label>
                        <div class="col-sm-12">
                            {{ Form::select('profession_id', collect(\App\Models\Clients\GeneralSubjectsFl::PROFESSIO),  $general->data->profession_id,  ['class' => 'form-control select2-ws']) }}
                        </div>
                    </div>
                @endif

                <div class="row col-sm-6">
                    <label class="col-sm-12 control-label">Категория лица</label>
                    <div class="col-sm-12">
                        {{ Form::select('person_category_id', collect(\App\Models\Clients\GeneralSubjects::PERSON_CATEGORY),  $general->person_category_id,  ['class' => 'form-control select2-ws']) }}
                    </div>
                </div>

                <div class="form-equally row col-sm-6">
                    <label class="col-sm-12 control-label">Статус сотрудничества</label>
                    <div class="col-sm-12">
                        {{ Form::select('status_work_id', collect(\App\Models\Clients\GeneralSubjects::STATUS_WORK),  $general->status_work_id,  ['class' => 'form-control select2-ws']) }}
                    </div>
                </div>


                <div class="row col-sm-12">
                    <label class="col-sm-12 control-label">Ответственный сотрудник</label>
                    <div class="col-sm-12">
                        {{ Form::select('user_id', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Нет', 0),  $general->user_id,  ['class' => 'form-control select2']) }}
                    </div>
                </div>

                    <div class="clear"></div>

                @else

                    @if($general->type_id == 0)
                        <div class="view-field">
                            <span class="view-label">Профессия</span>
                            <span class="view-value">{{\App\Models\Clients\GeneralSubjectsFl::PROFESSIO[$general->data->profession_id]}}</span>
                        </div>
                    @endif


                    <div class="view-field">
                        <span class="view-label">Категория лица</span>
                        <span class="view-value">{{\App\Models\Clients\GeneralSubjects::PERSON_CATEGORY[$general->person_category_id]}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Статус сотрудничества</span>
                        <span class="view-value">{{\App\Models\Clients\GeneralSubjects::STATUS_WORK[$general->status_work_id]}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Ответственный сотрудник</span>
                        <span class="view-value">{{($general->user)?$general->user->name:''}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Руководитель</span>
                        <span class="view-value">{{($general->user_parent)?$general->user_parent->name:''}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Куратор</span>
                        <span class="view-value">{{($general->user_curator)?$general->user_curator->name:''}}</span>
                    </div>

                @endif





                    @if($general->type_id == 0)

                        @if($state == 'edit')
                            <div class="row col-sm-12">
                                <label class="col-sm-12 control-label">Описание</label>
                                <div class="col-sm-12">
                                    {{ Form::textarea('comments',  $general->comments,  ['class' => 'form-control']) }}
                                </div>
                            </div>

                        @else

                            <div class="form-group">
                                <label class="col-sm-12 control-label">Описание</label>
                                <div class="col-sm-12">
                                    {{ $general->comments }}
                                </div>
                            </div>

                        @endif

                    @endif

                    @if($general->type_id == 1)

                        @include("general.subjects.info.ul.data.statutory_information.{$state}")

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

            $("#form-data").submit();

        }

    }


    function startMainFunctions() {

        initSelect2();

        $('.sum')
            .change(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .blur(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .keyup(function () {
                $(this).val(CommaFormatted($(this).val()));
            });

        initDataSubjects();
    }

    function updateInfo() {

        loaderShow();

        $.post('{{url("/general/subjects/edit/{$general->id}/update-info-podft")}}', $('#form-data').serialize(), function (response) {


            if (Boolean(response.state) === true) {

                selectTab(TAB_INDEX);

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




</script>