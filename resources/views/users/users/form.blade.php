<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2 class="inline-h1">Основная информация</h2>
        @if(isset($user))
            <span class="btn btn-info pull-right" onclick="openLogEvents('{{$user->id}}', 1, 0)"><i class="fa fa-history"></i> </span>
        @endif
    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.name') }}</label>
                    <div class="col-sm-3">
                        <input class="form-control surname" id="disabledInput" type="text" value=""  disabled>
                        <p class="help-block">Фамилия</p>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control name" id="disabledInput" type="text" value="" disabled>
                        <p class="help-block">Имя</p>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control lastname" id="disabledInput" type="text" value="" disabled>
                        <p class="help-block">Отчество</p>
                    </div>
                    {{ Form::hidden('name', old('name'), ['class' => 'form-control', 'readonly', 'required']) }}
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Статус</label>
                    <div class="col-sm-9">
                        {{ Form::select('status_user_id', [
                            \App\Models\Subject\Type::WORK => "Работает",
                            \App\Models\Subject\Type::NOT_WORK => "Уволен",
                        ], isset($user) ? $user->status_user_id : '',  ['class' => 'form-control status_user_id select2-ws']) }}
                    </div>
                </div>


                <div class="divider"></div>
                <br/>
                <div class="form-horizontal">

                    <input type="hidden" name="subject_type_id" class="subject_type_id" value="1"/>
                    {{--
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ trans('users/users.edit.subject_type') }}</label>
                        <div class="col-sm-9">
                            {{ Form::select('subject_type_id', [
                   \App\Models\Subject\Type::PHYSICAL => trans('users/users.edit.physical'),
                   \App\Models\Subject\Type::JURIDICAL => trans('users/users.edit.juridical'),
               ], isset($user) ? $user->subject_type_id : '',  ['class' => 'form-control subject_type_id select2-ws']) }}
                        </div>
                    </div>

                    --}}

                    @foreach($userInfoFields as $userInfoGroup => $userInfoGroupFields)

                        @foreach($userInfoGroupFields as $userInfoGroupField)


                            <div class="form-group {{ $userInfoGroup }}">
                                <label class="col-sm-3 control-label">{{ trans('users/users.edit.' . $userInfoGroupField) }}</label>
                                <div class="col-sm-9">
                                    {{ Form::text($userInfoGroupField, (isset($user) && $user->info ? $user->info[$userInfoGroupField] : ''), ['class' => 'form-control']) }}
                                </div>
                            </div>

                        @endforeach

                    @endforeach

                </div>

                <div class="divider"></div>
                <br/>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.email') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                        {{ Form::text('email', old('email'), ['class' => 'form-control', 'required']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Пароль <span class="required">*</span></label>
                    <div class="col-sm-9">
                        {{ Form::password('password', ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.work_phone') }}</label>
                    <div class="col-sm-9">
                        {{ Form::text('work_phone', old('work_phone'), ['class' => 'form-control phone']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.mobile_phone') }}</label>
                    <div class="col-sm-9">
                        {{ Form::text('mobile_phone', old('mobile_phone'), ['class' => 'form-control phone']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.role') }}</label>
                    <div class="col-sm-9">
                        {{ Form::select('role_id', $roles, old('role_id'), ['class' => 'form-control select2-ws', 'required']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.organization') }}</label>
                    <div class="col-sm-9">
                        {{ Form::select('organization_id', $organizations, old('organization_id'), ['class' => 'form-control select2-all', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.department') }}</label>
                    <div class="col-sm-9">
                        {{ Form::select('department_id', \App\Models\Settings\Department::all()->pluck('title', 'id'), old('department_id'), ['class' => 'form-control select2-ws', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Точка продаж</label>
                    <div class="col-sm-9">
                        {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), old('point_sale_id'), ['class' => 'form-control select2-ws', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.is_parent') }}</label>
                    <div class="col-sm-9">
                        {{ Form::checkbox('is_parent', 1, old('is_parent')) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Руководитель</label>
                    <div class="col-sm-9">
                        {{ Form::select('parent_id', \App\Models\User::getALLParent()->pluck('name', 'id')->prepend('Отсутствует', 0), isset($user) ? $user->parent_id : '',  ['class' => 'form-control select2']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Уведомления на почту</label>
                    <div class="col-sm-9">
                        {{ Form::checkbox('is_notification', 1, old('is_notification')) }}
                    </div>
                </div>

            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary pull-right">
                    Сохранить
                </button>
            </div>
        </div>

    </div>
</div>
@if(isset($user) && isset($user->organization) && $user->organization->org_type->is_provider == 1)
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2 class="inline-h1">Агентский договор</h2>
        <a class="btn btn-success btn-right doc_export_btn" href="/users/{{$user->id}}/generate_contract">Сформировать</a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        Агентский договор
                        <i class="fa fa-cog" style="font-size: 16px;cursor: pointer;" onclick="createAgentContract()"></i>
                    </label>
                    <div class="row col-sm-9">

                        <div class="col-sm-6">
                            {{ Form::text('agent_contract_title', old('agent_contract_title'), ['class' => 'form-control', 'placeholder'=>'Номер договора', 'id'=>'agent_contract_title']) }}
                        </div>

                        <div class="col-sm-3">
                            {{ Form::text('agent_contract_begin_date', ($user)?setDateTimeFormatRu($user->agent_contract_begin_date, 1):'', ['class' => 'form-control datepicker date', 'placeholder'=>'Дата начала', 'id'=>'agent_contract_begin_date']) }}
                        </div>

                        <div class="col-sm-3">
                            {{ Form::text('agent_contract_end_date', ($user)?setDateTimeFormatRu($user->agent_contract_end_date, 1):'', ['class' => 'form-control datepicker date', 'placeholder'=>'Дата окончания', 'id'=>'agent_contract_end_date']) }}
                        </div>


                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.сurator') }}</label>
                    <div class="col-sm-9">
                        {{ Form::select('curator_id', \App\Models\User::where('is_parent', '=', '1')->get()->pluck('name', 'id')->prepend('Отсутствует', 0), isset($user) ? $user->curator_id : '',  ['class' => 'form-control select2']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Тип продаж</label>
                    <div class="col-sm-9">
                        {{ Form::select('sales_condition', collect(\App\Models\Contracts\Contracts::SALES_CONDITION), isset($user) ? $user->sales_condition : 0,  ['class' => 'form-control select2-ws']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Финансовая группа</label>
                    <div class="col-sm-9">
                        {{ Form::select('financial_group_id', \App\Models\Settings\FinancialGroup::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('По умолчанию', 0), old('financial_group_id'), ['class' => 'form-control select2-ws', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Выдача БСО</label>
                    <div class="col-sm-9">
                        {{ Form::select('ban_level', collect([0=>'По умолчанию', 1=>'Частичная выдача', 2=>'Запрет'])->prepend('Отсутствует', -1), old('ban_level'), ['class' => 'form-control select2-ws', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Примечания</label>
                    <div class="col-sm-9">
                        {{ Form::text('ban_reason', old('ban_reason'), ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Продукты</label>
                    <div class="col-sm-9">
                        {{ Form::select('products_sale[]', \App\Models\Directories\Products::orderBy('title')->get()->pluck('title', 'id'), $user->getProductsSale(), ['class' => 'form-control select2-all', 'multiple' => true]) }}
                    </div>
                </div>



                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Продукт</th>
                        <th>Тариф</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user->getProductsSaleSpecialSettings() as $product_settings)
                        <tr style="cursor: pointer;" onclick="openTariffEdit('{{$product_settings->id}}')">
                            <td>{{$product_settings->title}}</td>
                            <td>{{$product_settings->tariff}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(isset($user) && $user->id>0)
                    <div class="form-group">
                        <div class="col-sm-11">
                            <span class="btn btn-primary btn-left" onclick="openFancyBoxFrame('{{url("/users/limit/?user_id={$user->id}")}}')">Редактировать лимиты</span><br>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endif


@section('js')
    <script>
        $(function () {
            $('.subject_type_id').change(function () {
                visibleUserTypeFields();
            });

            visibleUserTypeFields();

            visibleDriverFields();

            $('[name=second_name], [name=first_name], [name=middle_name], [name=title]').change(function () {
                setTitle();
            });

            $('[name=department_id]').change(function () {
                visibleDriverFields();
            });

            $('.image-input').change(function () {
                onChangeImageHandler(this, $('.car-image'))
            });

            searchFrontUser();


            if($('*').is('.select2-all')) {
                $('.select2-all').select2("destroy").select2({
                    width: '100%',
                    dropdownCssClass: "bigdrop",
                    //dropdownAutoWidth: true,
                    //top: container.bottom

                });
            }

        });

        function visibleUserTypeFields() {

            $('.juridical').hide();
            {{--

            if ($('.subject_type_id').val() == '{{ \App\Models\Subject\Type::PHYSICAL }}') {
                $('.juridical').hide();
                $('.physical').show();
            } else {
                $('.juridical').show();
                $('.physical').hide();
            }
            --}}
        }

        setTitle();

        function setTitle() {

            var title = '';

            if ($('.subject_type_id').val() == '{{ \App\Models\Subject\Type::PHYSICAL }}') {

                var firstName = $('[name=first_name]').val();

                var secondName = $('[name=second_name]').val();

                var middleName = $('[name=middle_name]').val();

                $(".surname").val(secondName);
                $(".name").val(firstName);
                $(".lastname").val(middleName);

                title += secondName + ' ' + firstName + ' ' + middleName;

            } else {

                title = $('[name=title]').val();
            }

            $('[name=name]').val(title);
        }

        function visibleDriverFields() {
            var selectedOption = $('[name=department_id] option:selected');
            var isDriver = selectedOption.data('user-type-id') == '{{\App\Models\Users\Type::DRIVER}}';
            $('.driver-field').toggleClass('hidden', !isDriver);
        }

        function triggerInputFile() {
            $('.image-input').trigger('click');
        }

        function onChangeImageHandler(obj, imageSelector) {
            if (obj.files && obj.files[0]) {
                var FR = new FileReader();
                FR.onload = function (e) {
                    imageSelector.prop('src', e.target.result);
                    imageSelector.removeClass('hidden');
                };
                FR.readAsDataURL(obj.files[0]);
            }
        }


        function createAgentContract() {

            $('#agent_contract_title').val('{{isset($user)?$user->id:0}}');
            $('#agent_contract_begin_date').val('{{date("d.m.Y")}}');
            $('#agent_contract_end_date').val('{{date("d.m.Y", strtotime("+ 1 year"))}}');

        }

        function searchFrontUser()
        {
            $('#front_user_title').suggestions({
                serviceUrl: "/users/actions/search_front_user/",
                type: "PARTY",
                count: 5,
                minChars: 3,
                onSelect: function (suggestion) {

                    $('#front_user_title').val(suggestion.value);
                    $('#front_user_id').val(suggestion.data.id);

                }
            });
        }

        function openTariffEdit(product_id) {
            openPage("/users/users/{{isset($user)?$user->id:0}}/tariff/"+product_id);
        }


    </script>
@append