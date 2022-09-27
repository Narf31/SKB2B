@extends('layouts.frame')

@section('title')

    Процедура


@stop

@section('content')

    {{ Form::open(['url' => url("/contracts/online/{$contract->id}/action/product/procedures/{$procedure_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">

        <label class="col-sm-6">Назваие</label>
        <label class="col-sm-6">Организация</label>
        <div class="col-sm-6">
            {{ Form::text('title', $procedure->title, ['class' => 'form-control', 'required']) }}
        </div>

        <div class="col-sm-6">
            {{ Form::text('organization_title', $procedure->organization_title, ['class' => 'form-control party-autocomplete', 'id'=>'organization_title']) }}
        </div>
    </div>


    <div class="form-group">

        <label class="col-sm-6">ИНН</label>
        <label class="col-sm-6">ОГРН</label>
        <div class="col-sm-6">
            {{ Form::text('inn', $procedure->inn, ['class' => 'form-control party-autocomplete', 'id'=>'inn']) }}
        </div>

        <div class="col-sm-6">
            {{ Form::text('ogrn', $procedure->ogrn, ['class' => 'form-control party-autocomplete', 'id'=>'ogrn']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label">Адрес</label>
        <div class="col-sm-12">
            {{ Form::text('address', $procedure->address, ['class' => 'form-control address-autocomplete', 'id'=>'address']) }}
            <input type="hidden" name="latitude" value="{{$procedure->latitude}}" id="latitude"/>
            <input type="hidden" name="longitude" value="{{$procedure->longitude}}" id="longitude"/>
        </div>

    </div>


    <div class="form-group">
        <label class="col-sm-12 control-label">Описание</label>
        <div class="col-sm-12">

            <textarea id="content" type="text" class="form-control" name="content_html">
                {{$procedure->content_html}}
            </textarea>

        </div>
    </div>

    {{Form::close()}}

@stop

@section('footer')

    @if($procedure_id > 0)

        <button class="btn btn-danger pull-left" onclick="deleteProcedure()">{{ trans('form.buttons.delete') }}</button>

    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

@section('js')
    <script src="/plugins/ckeditor/ckeditor.js"></script>

    <script>

        $(function () {
            CKEDITOR.replace('content');


            if($('*').is('.party-autocomplete')) {
                $(".party-autocomplete").suggestions({
                    serviceUrl: DADATA_AUTOCOMPLETE_URL,
                    token: DADATA_TOKEN,
                    type: "PARTY",
                    count: 5,
                    onSelect: function (suggestion) {
                        var data = suggestion.data;
                        var subjectType = $(this).data('party-type');


                        $('#organization_title').val(suggestion.value);
                        $('#inn').val(data.inn);
                        $('#ogrn').val(data.ogrn);


                    }
                });
            }


            if($('*').is('.address-autocomplete')) {
                $(".address-autocomplete").suggestions({
                    serviceUrl: DADATA_AUTOCOMPLETE_URL,
                    token: DADATA_TOKEN,
                    type: "ADDRESS",
                    count: 5,
                    onSelect: function (suggestion) {
                        var data = suggestion.data;
                        var subjectType = $(this).data('address-type');

                        //$('#address').val(data.country);

                        $('#latitude').val(data.geo_lat);
                        $('#longitude').val(data.geo_lon);


                    }
                });
            }



        });


        function deleteProcedure() {

            if (!customConfirm()) return false;

            $.post('{{url("/contracts/online/{$contract->id}/action/product/procedures/{$procedure_id}")}}', {
                _method: 'delete'
            }, function () {
                reloadTab();
            });

        }



    </script>
@endsection