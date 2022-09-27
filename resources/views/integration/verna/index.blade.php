@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h2>
            <span class="btn-left">
                ВЕРНА API
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


                {{Form::open(['url' => url("/integration/verna/"), 'id' => 'formData', 'class' => 'form-horizontal'])}}

                <div class="col-lg-12">
                    <div class="field form-col">
                        <label class="control-label">Тип</label>
                        {{ Form::select('type', collect(\App\Services\Integration\VernaAPI::TYPE), 'settings', ['class' => 'form-control', 'id'=>'type_id']) }}

                    </div>
                </div>


                {{Form::close()}}

                <div class="col-lg-12">
                    <span class="btn btn-success submit-btn pull-left" onclick="startLoad()">
                        Начать загрузку
                    </span>
                </div>


            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="progress">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="result" style="font-size: 24px;color: green;"></div>


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




        $(function () {


            $("#view_load").hide();

        });


        function startLoad()
        {
            $("#view_load").show();
            $("#result").html('');

            $.get('{{url("/integration/verna/updata")}}', $('#formData').serialize(), function (response) {

                if (parseInt(response.state) === 0) {
                    $("#result").html(response.msg);
                } else {
                    flashHeaderMessage(response.msg, 'danger');
                }

                $("#view_load").hide();
            }).always(function () {
                $("#view_load").hide();
            });
        }





    </script>


@endsection