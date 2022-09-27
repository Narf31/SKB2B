@extends('layouts.app')

@section('head')
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@append

@section('content')






        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">

                {{ Form::model($inquiry, ['url' => url("/security/$inquiry->id/send"), 'method' => 'post', 'class' => 'form-horizontal']) }}


                <div class="page-subheading">
                    <h2>Запрос #{{$inquiry->id}}</h2>
                    @if($inquiry->status == \App\Models\Security\Security::STATUS_WORK)
                        <button class="btn btn-danger" name="status" value="returns" type="submit">Вернуть</button>
                    @endif
                </div>

                <div class="block-inner">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="block-inner-heading">Основное</div>

                            <div class="info-group">
                                <label>Дата запроса</label>
                                <div class="value"><span>{{setDateTimeFormatRu($inquiry->created_at)}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Инициатор</label>
                                <div class="value"><span>{{$inquiry->send_user->name}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Телефон</label>
                                <div class="value"><span>{{$inquiry->send_user->mobile_phone}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Тип</label>
                                <div class="value"><span>{{$inquiry->type_inquiry_title($inquiry->type_inquiry)}}</span></div>
                            </div>

                            @if($inquiry->type_inquiry != \App\Models\Security\Security::TYPE_INQUIRY_EMERGENCY)
                            <div class="info-group">
                                <label>Ссылка</label>
                                <div class="value"><span><a href="{{$inquiry->type_link($inquiry->type_inquiry, $inquiry->object_id)}}" target="_blank">Открыть</a></span></div>
                            </div>
                            @endif

                            <div class="info-group">
                                <label>Статус</label>
                                <div class="value"><span>{{$inquiry->status_title($inquiry->status)}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Взятли в работу</label>
                                <div class="value"><span>{{setDateTimeFormatRu($inquiry->dates_work)}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Сотрудник</label>
                                <div class="value"><span>{{$inquiry->work_user->name}}</span></div>
                            </div>

                            <div class="info-group">
                                <label>Комментарий</label>
                                <div class="value"><br/>
                                    <div class="value"><span> </span></div>
                                </div>
                            </div>
                            <div class="info-group col-sm-12">
                                <textarea class="form-control" style="min-height: 200px" rows="3" name="comments">{{$inquiry->comments or ''}}</textarea>
                            </div>
                        </div>
                        <div class="info-group col-sm-12">
                            @if($inquiry->status == \App\Models\Security\Security::STATUS_WORK)
                                <button class="btn btn-primary pull-left" name="status" value="save" type="submit">{{ trans('form.buttons.save') }}</button>
                                <button class="btn btn-danger pull-right" name="status" value="archive" type="submit">В архив</button>
                            @endif
                        </div>
                    </div>
                </div>

                {{ Form::close() }}

            </div>

            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-8">



                @if($inquiry->type_inquiry == \App\Models\Security\Security::TYPE_INQUIRY_EMERGENCY)

                    @include('security.moduls.emergency', ['emergency' => $inquiry->emergency])


                @elseif($inquiry->type_inquiry == \App\Models\Security\Security::TYPE_INQUIRY_ORDER)

                    @include('security.moduls.order', ['events' => $inquiry->event_order])

                @else

                    @include('security.moduls.validation_databases')

                @endif



            </div>

        </div>


@endsection

