@extends('layouts.frame')


@section('title')

    Создать убыток

@stop

@section('content')


    {{ Form::open(['url' => url('/orders/damages/create'), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-12 control-label">Договор</label>
        <div class="col-sm-12">
            {{ Form::text('bso_title', '', ['class' => 'form-control', 'id'=>'bso_title']) }}
            <input type="hidden" name="bso_id" value="" id="bso_id" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label">Страхователь</label>
        <div class="col-sm-12">
            {{ Form::text('insurer_title', '', ['class' => 'form-control ', 'id'=>'insurer_title', 'readonly']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label">Примечание</label>
        <div class="col-sm-12">
            {{ Form::textarea('comments', '', ['class' => 'form-control', 'required']) }}
        </div>
    </div>


    {{Form::close()}}


@stop


@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary" id="button_save">Создать</button>

    <br/><br/>
@stop

@section('js')

    <script>




        $(function () {

            $("#button_save").hide();
            activSearchBsoContract('bso_title', 1);

        });


        function selectBsoContract(object_id, type, suggestion) {

            data = suggestion.data;
            $("#bso_title").val(data.bso_title);
            $("#bso_id").val(data.bso_id);
            $("#insurer_title").val(data.insurer);
            if(parseInt(data.bso_id) > 0){
                $("#button_save").show();
            }
        }



    </script>


@stop