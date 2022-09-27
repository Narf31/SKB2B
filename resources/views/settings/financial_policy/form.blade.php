
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/financial_policy.index.is_active') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_active', 1, old('is_active')) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/financial_policy.index.types_trailers_title') }}</label>
    <div class="col-sm-8">
        <select name="types_trailers_id" id="types_trailers_id" onchange="selectTitle();" class="form-control"></select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/financial_policy.index.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'id' => 'title']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/financial_policy.index.kv_km') }}</label>
    <div class="col-sm-8">
        {{ Form::text('kv_km', old('kv_km'), ['class' => 'form-control sum', 'id' => 'kv_km', 'onblur' => 'selectTitle()']) }}

    </div>
</div>


@section('js')

    <script>

        $(function () {

            getTypesTrailers();

        });

        function getTypesTrailers() {

            $.getJSON('{{ url("/dictionaries/trailers_types") }}', function(response){

                var id = +'{{ isset($financialPolicy) ? $financialPolicy->types_trailers_id : 0 }}';

                var options = getSelectOptions(response, id);

                $('#types_trailers_id').html(options);

            });

        }

        function selectTitle() {

            $('#title').val($('#types_trailers_id').find('option:selected').text() + " " + $('#kv_km').val());

        }



    </script>
@append
