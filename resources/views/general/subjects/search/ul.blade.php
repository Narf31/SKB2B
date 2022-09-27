@extends('layouts.frame')


@section('title')

    ЮЛ / ИП

@stop

@section('content')


    {{ Form::open(['url' => url('/general/subjects/find'), 'method' => 'post', 'class' => 'row form-horizontal']) }}

    <input type="hidden" name="contract_id" value="{{$contract_id}}"/>
    <input type="hidden" name="subjects" value="{{$subjects}}"/>
    <input type="hidden" name="type" value="{{$type}}"/>



    <div class="col-sm-12">
        <label class="col-sm-12 control-label">{{ trans('organizations/organizations.title') }} или ИНН</label>
        <div class="col-sm-12">
            {{ Form::text('title', '', ['class' => 'form-control party-autocomplete validate', 'data-party-type' => 'organizations', 'data-name' => 'organizations_title']) }}
        </div>
    </div>


    <div class="col-sm-6">
        <label class="col-sm-12 control-label" >ИНН</label>
        <div class="col-sm-12">
            {{ Form::text('inn', '', ['class' => 'form-control party-autocomplete validate', 'readonly', 'data-name' => 'organizations_inn', 'data-party-type' => 'organizations']) }}
        </div>
    </div>


    <div class="col-sm-6">
        <label class="col-sm-12 control-label">ОГРН</label>
        <div class="col-sm-12">
            {{ Form::text('ogrn', '', ['class' => 'form-control validate', 'readonly', 'data-name' => 'organizations_ogrn']) }}
        </div>
    </div>


    {{Form::close()}}


@stop


@section('footer')

    <button onclick="createClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.create') }}</button>

@endsection


@section('js')

    <script>

        function createClients()
        {

            if(validate()){
                loaderShow();
                submitForm();

            }

        }


        $(function(){


            $(".party-autocomplete").suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "PARTY",
                count: 5,
                onSelect: function (suggestion) {
                    var data = suggestion.data;
                    var subjectType = $(this).data('party-type');

                    $('[data-name=' + subjectType + '_title]').val(suggestion.value);
                    $('[data-name=' + subjectType + '_inn]').val(data.inn);
                    $('[data-name=' + subjectType + '_ogrn]').val(data.ogrn);

                    $("#city_kladr").val(data.address.data.city_kladr_id);
                    $("#city_title").val(data.address.data.city_with_type);

                }
            });


        });


    </script>


@endsection