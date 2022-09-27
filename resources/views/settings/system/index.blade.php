@extends('layouts.app')

@section('content')



<div class="page-heading">
    <h1>{{ trans('menu.system') }}</h1>

</div>

{{ Form::open(['url' => url('/settings/system'), 'method' => 'post', "autocomplete" =>"off", 'files' => true]) }}




<div class="block-main">
    <div class="block-sub">
        <div class="form-horizontal">

            <h4>Базовые настройки</h4>
            <br/>
            <div class="form-group">
                <label class="col-sm-3 control-label">Название</label>
                <div class="col-sm-9">
                    {{ Form::text('base[system_name]', \App\Models\Settings\SettingsSystem::getDataParam('base', 'system_name'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Техподдержка</label>
                <div class="col-sm-9">
                    <div class="row form-horizontal">
                        <div class="col-sm-6">
                            {{ Form::text('base[phone]', \App\Models\Settings\SettingsSystem::getDataParam('base', 'phone'), ['class' => 'form-control phone', 'placeholder'=>'Телефо']) }}
                        </div>
                        <div class="col-sm-6">
                            {{ Form::text('base[email]', \App\Models\Settings\SettingsSystem::getDataParam('base', 'email'), ['class' => 'form-control', 'placeholder'=>'Email']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>


            <h4>Интеграция: DaData</h4>
            <br/>

            <div class="form-group">
                <label class="col-sm-3 control-label">URL</label>
                <div class="col-sm-9">
                    {{ Form::text('dadata[url]', \App\Models\Settings\SettingsSystem::getDataParam('dadata', 'url'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Token API</label>
                <div class="col-sm-9">
                    {{ Form::text('dadata[token_api]', \App\Models\Settings\SettingsSystem::getDataParam('dadata', 'token_api'), ['class' => 'form-control']) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Token Секретный</label>
                <div class="col-sm-9">
                    {{ Form::text('dadata[token_secret]', \App\Models\Settings\SettingsSystem::getDataParam('dadata', 'token_secret'), ['class' => 'form-control']) }}
                </div>
            </div>





            <div class="divider"></div>


            <h4>Интеграция: Amicus</h4>
            <br/>

            <div class="form-group">
                <label class="col-sm-3 control-label">URL</label>
                <div class="col-sm-9">
                    {{ Form::text('amicus[url]', \App\Models\Settings\SettingsSystem::getDataParam('amicus', 'url'), ['class' => 'form-control']) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Token Секретный</label>
                <div class="col-sm-9">
                    {{ Form::text('amicus[secret]', \App\Models\Settings\SettingsSystem::getDataParam('amicus', 'secret'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="divider"></div>


            <h4>Интеграция: oData-1C</h4>
            <br/>

            <div class="form-group">
                <label class="col-sm-3 control-label">URL</label>
                <div class="col-sm-9">
                    {{ Form::text('odata[url]', \App\Models\Settings\SettingsSystem::getDataParam('odata', 'url'), ['class' => 'form-control']) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Login</label>
                <div class="col-sm-4">
                    {{ Form::text('odata[login]', \App\Models\Settings\SettingsSystem::getDataParam('odata', 'login'), ['class' => 'form-control']) }}
                </div>
                <div class="col-sm-5">
                    {{ Form::text('odata[pass]', \App\Models\Settings\SettingsSystem::getDataParam('odata', 'pass'), ['class' => 'form-control']) }}
                </div>
            </div>






            <div class="divider"></div>

            <h4>Интеграция: Контур.Призма</h4>
            <br/>

            <div class="form-group">
                <label class="col-sm-3 control-label">URL</label>
                <div class="col-sm-9">
                    {{ Form::text('сontourPrism[url]', \App\Models\Settings\SettingsSystem::getDataParam('сontourPrism', 'url'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">apiKeyValue</label>
                <div class="col-sm-9">
                    {{ Form::text('сontourPrism[apiKeyValue]', \App\Models\Settings\SettingsSystem::getDataParam('сontourPrism', 'apiKeyValue'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">bankId</label>
                <div class="col-sm-9">
                    {{ Form::text('сontourPrism[bankId]', \App\Models\Settings\SettingsSystem::getDataParam('сontourPrism', 'bankId'), ['class' => 'form-control']) }}
                </div>
            </div>



            <div class="divider"></div>

            {{--
            <h4>Интеграция: ВЕРНА API</h4>
            <br/>

            <div class="form-group">
                <label class="col-sm-3 control-label">URL</label>
                <div class="col-sm-9">
                    {{ Form::text('verna[url]', \App\Models\Settings\SettingsSystem::getDataParam('verna', 'url'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Логин</label>
                <div class="col-sm-9">
                    {{ Form::text('verna[login]', \App\Models\Settings\SettingsSystem::getDataParam('verna', 'login'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Пароль</label>
                <div class="col-sm-9">
                    {{ Form::text('verna[password]', \App\Models\Settings\SettingsSystem::getDataParam('verna', 'password'), ['class' => 'form-control']) }}
                </div>
            </div>
--}}



            <div class="divider"></div>




            <!-- end -->

            <br/>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary pull-right">
                        Сохранить
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


{{ Form::close() }}

@endsection

