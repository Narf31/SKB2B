<table class="table table-bordered">

    <tr>
        <td>{{ trans('settings/financial_policy.index.is_active') }}</td>
        <td>
            {{ Form::checkbox('is_active', 1, old('is_active')) }}
        </td>
    </tr>

    <tr>
        <td>{{ trans('settings/financial_policy.index.types_trailers_title') }}</td>
        <td>
            <select name="types_trailers_id" id="types_trailers_id" onchange="selectTitle();" class="form-control"></select>
        </td>
    </tr>
    <tr>
        <td>{{ trans('settings/financial_policy.index.title') }}</td>
        <td>
            {{ Form::text('title', old('title'), ['class' => 'form-control', 'id' => 'title']) }}
        </td>
    </tr>
    <tr>
        <td>{{ trans('settings/financial_policy.index.kv_km') }}</td>
        <td>
            {{ Form::text('kv_km', old('kv_km'), ['class' => 'form-control', 'id' => 'kv_km', 'onblur' => 'selectTitle()']) }}
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="submit" class="btn btn-theme-dark pull-right" value="{{ trans('form.buttons.save') }}"/>
        </td>
    </tr>
</table>

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
