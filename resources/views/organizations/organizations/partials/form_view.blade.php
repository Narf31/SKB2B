

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">



                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.is_actual') }}</label>
                    <div class="col-sm-4">
                        {{((int)$organization->is_actual==1)?"Да":"Нет"}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.type') }}</label>
                    <div class="col-sm-8">
                        {{$organization->org_type->title}}
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label">Руководитель организации</label>
                    <div class="col-sm-8">
                        {{($organization->parent_user)?$organization->parent_user->name:''}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.title') }}</label>
                    <div class="col-sm-8">
                        {{$organization->title}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.title_doc') }}</label>
                    <div class="col-sm-8">
                        {{$organization->title_doc}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.general_manager') }}</label>
                    <div class="col-sm-8">
                        {{$organization->general_manager}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.inn') }}</label>
                    <div class="col-sm-8">
                        {{$organization->inn}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">КПП</label>
                    <div class="col-sm-8">
                        {{$organization->kpp}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.address') }}</label>
                    <div class="col-sm-8">
                        {{$organization->address}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Фактический адрес</label>
                    <div class="col-sm-8">
                        {{$organization->fact_address}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Контактное лицо</label>
                    <div class="col-sm-8">
                        {{$organization->user_contact_title}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.phone') }}</label>
                    <div class="col-sm-8">
                        {{$organization->phone}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.email') }}</label>
                    <div class="col-sm-8">
                        {{$organization->email}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.comment') }}</label>
                    <div class="col-sm-8">
                        {{$organization->comment}}
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


@if(isset($organization) && $organization->org_type->is_provider == 0)

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
        <div class="block-main">
            <div class="block-sub">
                <div class="row form-horizontal">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Агентский договор</span>
                            <span class="view-value">{{ $organization->agent_contract_title }} действует {{ setDateTimeFormatRu($organization->agent_contract_begin_date, 1) }} - {{ setDateTimeFormatRu($organization->agent_contract_begin_date, 1) }}</span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Куратор</span>
                            <span class="view-value">{{ $organization->curator?$organization->curator->name:'' }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Финансовая группа</span>
                            <span class="view-value">{{ $organization->financial_group?$organization->financial_group->title:'' }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Выдача БСО</span>
                            <span class="view-value">{{ collect([-1 => 'Отсутствует', 0=>'По умолчанию', 1=>'Частичная выдача', 2=>'Запрет'])[$organization->ban_level] }}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Примечания</span>
                            <span class="view-value">{{ $organization->ban_reason }}</span>
                        </div>
                    </div>

                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="col-sm-12 control-label">Продукты</label>
                        <div class="col-sm-12">
                            {!! $organization->getProductsSale(1) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

<script>

    $(function(){

    });

    function initTab() {
        startMainFunctions();

    }




</script>