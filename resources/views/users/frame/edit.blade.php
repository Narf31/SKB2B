@extends('layouts.frame')


@section('title')

    Пользователь {{$user->organization->title}}

@stop

@section('content')


    {{ Form::open(['url' => url("/users/frame/?user_id=".((int)$user->id)), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <input type="hidden" name="organization_id" value="{{$user->organization_id}}"/>


    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.name') }}</label>
            <div class="col-sm-3">
                <input class="form-control surname" id="disabledInput" type="text" value="" disabled>
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
                {{ Form::select('status_user_id', collect(\App\Models\User::STATUS_USER), isset($user) ? $user->status_user_id : '',  ['class' => 'form-control status_user_id']) }}
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.email') }}</label>
            <div class="col-sm-9">
                {{ Form::text('email', isset($user) ? $user->email : '', ['class' => 'form-control', 'required']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Пароль</label>
            <div class="col-sm-9">
                {{ Form::password('password', ['class' => 'form-control', '']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.work_phone') }}</label>
            <div class="col-sm-9">
                {{ Form::text('work_phone', isset($user) ? $user->work_phone : '', ['class' => 'form-control phone']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.mobile_phone') }}</label>
            <div class="col-sm-9">
                {{ Form::text('mobile_phone', isset($user) ? $user->mobile_phone : '', ['class' => 'form-control phone']) }}
            </div>
        </div>



        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.department') }}</label>
            <div class="col-sm-9">
                <select class="form-control" name="department_id" required>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" data-user-type-id="{{ $department->user_type_id }}"
                                @if(isset($user) && $user->department_id == $department->id ) selected @endif
                        >
                            {{ $department->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>



        {{--<div class="form-group">
            <label class="col-sm-3 control-label">Филиал</label>
            <div class="col-sm-9">
                {{ Form::select('filial_id', \App\Models\Settings\Filials::all()->pluck('title', 'id'), isset($user) ? $user->filial_id : 0, ['class' => 'form-control', 'required']) }}
            </div>
        </div>--}}

        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.is_parent') }} {{ Form::checkbox('is_parent', 1, isset($user) ? $user->is_parent : 0,  ['id' => 'is_parent']) }}</label>
            <div class="col-sm-9">
                {{ Form::select('parent_id', \App\Models\User::where('organization_id', '=', $user->organization_id)->where('is_parent', '=', '1')->where('id', '!=', (isset($user) ? $user->id : 0))->get()->pluck('name', 'id')->prepend('Нет', 0), isset($user) ? $user->parent_id : '',  ['class' => 'form-control select2-all parent_id']) }}

            </div>
        </div>

    </div>


    <div class="divider"></div>
    <br/>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/users.edit.subject_type') }}</label>
            <div class="col-sm-9">
                {{ Form::select('subject_type_id', [
       \App\Models\Subject\Type::PHYSICAL => trans('users/users.edit.physical'),
   ], isset($user) ? $user->subject_type_id : '',  ['class' => 'form-control subject_type_id']) }}
            </div>
        </div>


        @foreach($userInfoFields as $userInfoGroup => $userInfoGroupFields)

            @foreach($userInfoGroupFields as $userInfoGroupField)


                <div class="form-group {{ $userInfoGroup }}">
                    <label class="col-sm-3 control-label">{{ trans('users/users.edit.' . $userInfoGroupField) }}</label>
                    <div class="col-sm-9">
                        {{ Form::text($userInfoGroupField, (isset($user) && $user->info ? $user->info->$userInfoGroupField : ''), ['class' => 'form-control']) }}
                    </div>
                </div>

            @endforeach

        @endforeach


    </div>




    {{Form::close()}}




@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop




@section('js')
    <script>
        $(function () {
            $('.subject_type_id').change(function () {
                visibleUserTypeFields();
            });

            $('#is_parent').click(function () {
                visibleUserParent();
            });

            visibleUserParent();
            
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

        });

        function visibleUserTypeFields() {
            if ($('.subject_type_id').val() == '{{ \App\Models\Subject\Type::PHYSICAL }}') {
                $('.juridical').hide();
                $('.physical').show();
            } else {
                $('.juridical').show();
                $('.physical').hide();
            }
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
        
        
        function visibleUserParent() {
            if ($("#is_parent").is(':checked')) {
                $('.parent_id').hide();
            }else{
                $('.parent_id').show();
            }
        }
        
        
        
    </script>
@append