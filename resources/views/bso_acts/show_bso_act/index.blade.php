@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h2 class="inline-h1">{{$act->act_name}} #{{$act->act_number}}</h2>
        <a class="btn btn-primary btn-right pull-right doc_export_btn" href="/bso_acts/export/{{$act->id}}/">Сформировать Акт</a>
    </div>


    <div class="row form-horizontal" id="main_container" style="margin-top: 15px">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Дата/время</label>
                            <div class="col-sm-12">
                                {{setDateTimeFormatRu($act->time_create)}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Тип акта</label>
                            <div class="col-sm-12">
                                {!!$act->type->title!!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Статус БСО</label>
                            <div class="col-sm-12">
                                {{$act->bso_state->title}}
                            </div>
                        </div>


                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Выдал</label>
                            <div class="col-sm-12">
                                {{($act->user_from)?$act->user_from->name:''}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Получил</label>
                            <div class="col-sm-12">
                                {{($act->user_to)?$act->user_to->name:''}}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Сотрудник</label>
                            <div class="col-sm-12">
                                {{($act->bso_manager)?$act->bso_manager->name:''}}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-horizontal">
                    {!! $bso_table !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')

    <script>

        $(function () {


        });




    </script>


@stop