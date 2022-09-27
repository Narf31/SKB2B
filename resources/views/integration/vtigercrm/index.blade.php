@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h2>
            <span class="btn-left">
                Имиграция VtigerCRM
            </span>
        </h2>
    </div>

    <div class="form-group">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="control">
        </div>
    </div>

    <div class="form-group" style="margin-left: 0; margin-right: 0;">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">




            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">


                {{Form::open(['url' => url("/enrichment/m5_back_old/start-info"), 'id' => 'formData', 'class' => 'form-horizontal'])}}

                <input type="hidden" name="count_all" id="count_all" value="0"/>




                <div class="col-lg-12">

                    <span class="btn btn-success submit-btn pull-left" onclick="startConnection()">
                        Привязать связи
                    </span>


                    <span class="btn btn-danger submit-btn pull-right" onclick="clearSystem()">
                        Очистить систему
                    </span>
                </div>

                <div class="col-lg-12">
                    <div class="field form-col">
                        <label class="control-label">Тип</label>
                        {{ Form::select('type', collect(\App\Services\Integration\VtigerCRM::TYPE), 'settings', ['class' => 'form-control', 'id'=>'type_id']) }}

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="field form-col">
                        <label class="control-label">Ничиная</label>
                        {{ Form::text('start', 1, ['class' => 'form-control', 'id'=>'start']) }}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="field form-col">
                        <label class="control-label">Кол-во по</label>
                        {{ Form::text('counts', 100, ['class' => 'form-control', 'id'=>'counts']) }}
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="field form-col ">
                        <label class="control-label">Удалять при старте</label>
                        {{ Form::select('delete', collect([0=>'Нет', 1=>'Да']), 0, ['class' => 'form-control']) }}
                    </div>
                </div>


                {{Form::close()}}

                <div class="col-lg-12">
                    <span class="btn btn-success submit-btn pull-left" onclick="startLoad()">
                        Начать загрузку
                    </span>
                    <span class="btn btn-danger submit-btn pull-right" onclick="STATE=1;">
                        Стоп
                    </span>
                </div>


            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="progress">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                <div id="progressbar"></div>

                <div class="col-lg-6">
                    <div class="field form-col pull-left">
                        <label class="control-label" id="start_view">0</label>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="field form-col pull-right">
                        <label class="control-label" id="total">10000</label>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="view_load">
                    <center><img src="/assets/img/spinner.svg" ></center>
                </div>
                
            </div>



        </div>
    </div>




@endsection

@section('js')



    <script>


        var STATE = 0;//0 продолжаем 1 стоп



        $(function () {

            $("#progress").hide();
            $("#view_load").hide();

            $("#progressbar").progressbar({
                value: 0
            });





        });


        function startLoad()
        {
            $.post('{{url("/integration/vtigercrm/start-info")}}', $('#formData').serialize(), function (response) {

                if (parseInt(response.state) === 0) {
                    $("#count_all").val(response.count_all);
                    $("#total").html($("#count_all").val());


                    STATE = 0;
                    $("#progressbar").progressbar({
                        value: 0
                    });
                    updateLoad();
                } else {
                    flashHeaderMessage(response.msg, 'danger');
                }

            }).always(function () {

            });
        }


        function updateLoad()
        {

            $("#progress").show();

            if(STATE == 0){

                $("#view_load").show();
                $.post('{{url("/integration/vtigercrm/updata-info")}}', $('#formData').serialize(), function (response) {

                    $("#view_load").hide();

                    if(STATE == 0){
                        STATE = parseInt(response.state);
                    }

                    if(response.start){
                        $("#start").val(response.start);
                        $("#start_view").html(response.start*$("#counts").val());
                    }



                    $("#progressbar").progressbar({
                        value: parseInt(response.progressbar)
                    });

                    if (STATE == 0) {
                        updateLoad();
                    }else{
                        $("#view_load").hide();
                    }

                }).always(function () {

                });

            }else{
                $("#view_load").hide();
            }
        }

        function startConnection()
        {
            $.post('{{url("/integration/vtigercrm/connection")}}', $('#formData').serialize(), function (response) {

                if (parseInt(response.state) === 0) {
                    flashHeaderMessage(response.msg, 'success');

                } else {
                    flashHeaderMessage(response.msg, 'danger');
                }

            }).always(function () {

            });
        }

        function clearSystem()
        {
            $.post('{{url("/integration/vtigercrm/clear-system")}}', {}, function (response) {

                if (parseInt(response.state) === 0) {
                    flashHeaderMessage(response.msg, 'success');
                } else {
                    flashHeaderMessage(response.msg, 'danger');
                }

            }).always(function () {

            });
        }



    </script>


@endsection