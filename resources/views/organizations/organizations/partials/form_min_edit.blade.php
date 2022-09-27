

{{ Form::model($organization, ['url' => url($send_urls), 'method' => 'put',  'class' => 'form-horizontal']) }}

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
                        {{$organization->parent_user->name}}
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
                        {{ Form::text('fact_address', $organization->fact_address, ['class' => 'form-control address-autocomplete ', 'data-name' => 'organizations_address', 'data-address-type' => 'organizations']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Контактное лицо</label>
                    <div class="col-sm-8">
                        {{ Form::text('user_contact_title', $organization->user_contact_title, ['class' => 'form-control fio-autocomplete', '']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.phone') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('phone', $organization->phone, ['class' => 'form-control phone', '']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.email') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('email', $organization->email, ['class' => 'form-control', '']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.comment') }}</label>
                    <div class="col-sm-8">
                        {{ Form::textarea('comment', $organization->comment, ['class' => 'form-control', '']) }}
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right">
                            Сохранить
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{Form::close()}}


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