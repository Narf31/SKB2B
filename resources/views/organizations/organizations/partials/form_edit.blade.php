

@if(auth()->user()->hasPermission('directories', 'organizations_edit'))
{{ Form::model($organization, ['url' => url($send_urls), 'method' => 'put',  'class' => 'form-horizontal']) }}
@endif

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">


                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.is_actual') }}</label>
                    <div class="col-sm-4">
                        {{ Form::checkbox('is_actual', 1, ((int)$organization->id>0)?$organization->is_actual:1) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Основная компания</label>
                    <div class="col-sm-4">
                        {{ Form::checkbox('is_main_company', 1, ((int)$organization->id>0)?$organization->is_main_company:0) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.type') }}</label>
                    <div class="col-sm-8">
                        {{ Form::select('org_type_id', \App\Models\Settings\TypeOrg::where('is_actual', 1)->get()->pluck('title', 'id'),  $organization->org_type_id,  ['class' => 'form-control select2-all']) }}
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label">Руководитель организации</label>
                    <div class="col-sm-8">
                        {{ Form::select('parent_user_id', \App\Models\User::where('is_parent', 1)->where('organization_id', (isset($organization) ? $organization->id : -1))->orderBy('name')->get()->pluck('name', 'id')->prepend('Нет', 0),  (isset($organization) ? $organization->parent_user_id : auth()->id()),  ['class' => 'form-control select2-all']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.title') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('title', $organization->title, ['class' => 'form-control party-autocomplete ', 'data-party-type' => 'organizations', 'data-name' => 'organizations_title']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.title_doc') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('title_doc', $organization->title_doc, ['class' => 'form-control ', 'data-name' => 'organizations_title_doc']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.general_manager') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('general_manager', $organization->general_manager, ['class' => 'form-control ', 'data-name' => 'organizations_general_manager']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.inn') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('inn', $organization->inn, ['class' => 'form-control party-autocomplete ', 'data-name' => 'organizations_inn', 'data-party-type' => 'organizations']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">КПП</label>
                    <div class="col-sm-8">
                        {{ Form::text('kpp', $organization->kpp, ['class' => 'form-control', 'data-name' => 'organizations_kpp']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.address') }}</label>
                    <div class="col-sm-8">
                        {{ Form::text('address', $organization->address, ['class' => 'form-control address-autocomplete ', 'data-name' => 'organizations_address', 'data-address-type' => 'organizations']) }}
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


                @if(auth()->user()->hasPermission('directories', 'organizations_edit'))
                <div class="form-group">
                    <div class="col-sm-12">
                        <span id="delete_org" class="btn btn-danger col-sm-2 pull-left" onclick="myOrgDelete()">
                            Удалить
                        </span>
                        <button type="submit" class="btn btn-primary pull-right">
                            Сохранить
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


@if(auth()->user()->hasPermission('directories', 'organizations_edit'))

    @if(isset($organization) && isset($organization->org_type) && $organization->org_type->is_provider == 0)

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Агентский договор
                            </label>
                            <div class="row col-sm-9">

                                <div class="col-sm-6">
                                    {{ Form::text('agent_contract_title', $organization->agent_contract_title, ['class' => 'form-control', 'placeholder'=>'Номер договора', 'id'=>'agent_contract_title']) }}
                                </div>

                                <div class="col-sm-3">
                                    {{ Form::text('agent_contract_begin_date', setDateTimeFormatRu($organization->agent_contract_begin_date, 1), ['class' => 'form-control datepicker date', 'placeholder'=>'Дата начала', 'id'=>'agent_contract_begin_date']) }}
                                </div>

                                <div class="col-sm-3">
                                    {{ Form::text('agent_contract_end_date', setDateTimeFormatRu($organization->agent_contract_begin_date, 1), ['class' => 'form-control datepicker date', 'placeholder'=>'Дата окончания', 'id'=>'agent_contract_end_date']) }}
                                </div>


                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ trans('users/users.edit.сurator') }}</label>
                            <div class="col-sm-9">
                                {{ Form::select('curator_id', \App\Models\User::getALLCurator()->pluck('name', 'id')->prepend('Отсутствует', 0), $organization->curator_id,  ['class' => 'form-control select2']) }}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Точка продаж</label>
                            <div class="col-sm-9">
                                {{ Form::select('points_sale_id', \App\Models\Settings\PointsSale::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('Не выбрано', 0), $organization->points_sale_id, ['class' => 'form-control select2-all', 'required']) }}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Финансовая группа</label>
                            <div class="col-sm-9">
                                {{ Form::select('financial_group_id', \App\Models\Settings\FinancialGroup::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('По умолчанию', 0), $organization->financial_group_id, ['class' => 'form-control select2-ws', 'required']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Выдача БСО</label>
                            <div class="col-sm-9">
                                {{ Form::select('ban_level', collect([0=>'По умолчанию', 1=>'Частичная выдача', 2=>'Запрет'])->prepend('Отсутствует', -1), $organization->ban_level, ['class' => 'form-control select2-ws', 'required']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Примечания</label>
                            <div class="col-sm-9">
                                {{ Form::text('ban_reason', $organization->ban_reason, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Продукты</label>
                            <div class="col-sm-9">
                                {{ Form::select('products_sale[]', \App\Models\Directories\Products::orderBy('title')->get()->pluck('title', 'id'), $organization->getProductsSale(), ['class' => 'form-control select2-all', 'multiple' => true]) }}
                            </div>
                        </div>

                        @if(auth()->user()->hasPermission('directories', 'organizations_edit'))


                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Продукт</th>
                                    <th>Тариф</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organization->getProductsSaleSpecialSettings() as $product_settings)
                                    <tr @if(auth()->user()->hasPermission('directories', 'organizations_edit')) style="cursor: pointer;" onclick="openTariffEdit('{{$product_settings->id}}')" @endif>
                                        <td>{{$product_settings->title}}</td>
                                        <td>{{$product_settings->tariff}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <script>


                                function openTariffEdit(product_id) {
                                    openPage("/directories/organizations/organizations/{{$organization->id}}/tariff/"+product_id);
                                }



                            </script>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary pull-left">
                                        Сохранить
                                    </button>

                                    @if($organization->parent_user_id)

                                    <a class="btn btn-success btn-right doc_export_btn" href="/users/{{$organization->parent_user_id}}/generate_contract">Сформировать</a>

                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>






        </div>


    @else

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Точка продаж</label>
                            <div class="col-sm-9">
                                {{ Form::select('points_sale_id', \App\Models\Settings\PointsSale::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('Не выбрано', 0), $organization->points_sale_id, ['class' => 'form-control select2-all', 'required']) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    @endif




@endif

{{Form::close()}}

<script>

    $(function(){

    });

    function initTab() {
        startMainFunctions();

    }
    @if(auth()->user()->hasPermission('directories', 'organizations_edit'))
    function myOrgDelete() {
        if(confirm("Удалить организацию?")){
            $.post('{{url("/directories/organizations/{$organization->id}/delete")}}', {}, function(){
                location.href = '/directories/organizations/organizations/';
            });
        }

    }
    @endif

</script>