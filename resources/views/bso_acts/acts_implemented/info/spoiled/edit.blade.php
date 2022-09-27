@extends('layouts.frame')


@section('title')

    БСО @if(isset($bso)) {{$bso->bso_title}} @endif

@stop

@section('content')


    {{ Form::open(['url' => url('/bso_acts/acts_implemented/spoiled/edit/'), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}

    <div class="form-group">
        <label class="col-sm-4 control-label">БСО</label>
        <div class="col-sm-8">
            {{ Form::text('bso_title', (isset($bso))?$bso->bso_title:'', ['class' => 'form-control', 'id'=>'bso_title', (isset($bso))?'disabled':'']) }}
            <input type="hidden" name="bso_id" id="bso_id" value="{{(isset($bso))?$bso->id:0}}" />
            <input type="hidden" name="bso_supplier_id" id="bso_supplier_id" />
            <input type="hidden" name="insurance_companies_id" id="insurance_companies_id"/>
            <input type="hidden" name="product_id" id="product_id" />
            <input type="hidden" name="agent_id" id="agent_id" value="{{$agent_id}}" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">СК</label>
        <div class="col-sm-8">
            {{ Form::text('sk_title', (isset($bso))?$bso->supplier->title:'', ['class' => 'form-control', 'id'=>'sk_title', 'disabled']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Агент</label>
        <div class="col-sm-8">
            {{ Form::text('agent_title', (isset($bso))?$bso->user->name:'', ['class' => 'form-control', 'id'=>'agent_title', 'disabled']) }}
        </div>
    </div>



    <div class="form-group">
        <label class="col-sm-4 control-label">
            Скан <br/>
            @if(isset($bso) && $bso->file_id > 0)
                <a href="{{ url($bso->scan->url) }}" target="_blank">{{$bso->scan->original_name}}</a>
            @endif
        </label>
        <div class="col-sm-8">
            <input type="file" name="file"/>
        </div>
    </div>




    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">Испортить</button>

@stop




@section('js')

    <script>




        $(function () {

            @if(!$bso)
                activSearchBso("bso_title", '', -1);
            @endif


        });


        function selectBso(object_id, key, type, suggestion)
        {

            var data = suggestion.data;

            $('#bso_id'+key).val(data.bso_id);
            $('#bso_supplier_id'+key).val(data.bso_supplier_id);
            $('#insurance_companies_id'+key).val(data.insurance_companies_id);
            $('#product_id'+key).val(data.product_id);
            $('#agent_id'+key).val(data.agent_id);
            $('#sk_title').val(data.bso_sk);
            $('#agent_title').val(data.agent_name);

        }




    </script>


@stop

