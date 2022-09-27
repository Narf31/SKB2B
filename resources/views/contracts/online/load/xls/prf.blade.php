@extends('layouts.frame')

@section('title')


    Загрузка XLS

    <a href="/sample/prf/load.xlsx" class="pull-right">Образец XLS</a>

@stop

@section('content')


    <div style="min-height: 350px">
        <div id="form-load">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {!! Form::open(['url'=>url("/contracts/online/{$contract_id}/load/xls/data-file"),'method' => 'post', 'class' => 'addManyDocForm dropzone_' ]) !!}
                <div class="dz-message" data-dz-message>
                    <p>Перетащите сюда файл</p>
                    <p class="dz-link">или выберите с диска</p>
                </div>
                {!! Form::close() !!}
            </div>

        </div>

        <div id="form-data" style="display: none;">

            {{ Form::open(['url' => url("/contracts/online/{$contract_id}/load/xls/prf"), 'method' => 'post', 'class' => 'form-horizontal', 'id'=>'formXLS']) }}

            <input type="hidden" name="file_name">

            <div class="form-group">
                <label class="col-sm-12 control-label">Фамилия и имя</label>
                <div class="clear"></div>
                <div class="col-sm-6">
                    {{ Form::select('excel_columns[user_title]', [], null, ['class' => 'form-control columns-select', 'required']) }}
                </div>
                <div class="col-sm-6">
                    {{ Form::text('default[user_title]', '', ['class' => 'form-control', 'required']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-12 control-label">Дата рождения</label>
                <div class="clear"></div>
                <div class="col-sm-6">
                    {{ Form::select('excel_columns[birth_date]', [], null, ['class' => 'form-control columns-select', 'required']) }}
                </div>
                <div class="col-sm-6">
                    {{ Form::text('default[birth_date]', '', ['class' => 'form-control', 'required']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-12 control-label">Пол</label>
                <div class="clear"></div>
                <div class="col-sm-6">
                    {{ Form::select('excel_columns[gender]', [], null, ['class' => 'form-control columns-select', 'required']) }}
                </div>
                <div class="col-sm-6">
                    {{ Form::text('default[gender]', '', ['class' => 'form-control', 'required']) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-12 control-label">Гражданство</label>
                <div class="clear"></div>
                <div class="col-sm-12">

                    {{ Form::select("default[citizenship_id]", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'), 51, ['class' => 'form-control select2-all']) }}

                </div>

            </div>


            {{Form::close()}}

        </div>
    </div>





@stop

@section('footer')

    <div style="min-height: 30px">
        <button onclick="sendForm()" type="submit" class="btn btn-primary btn-save" style="display: none;">Отправить</button>
    </div>




@stop

@section('js')


    <script>


        $(function () {


            $(".addManyDocForm").dropzone({
                paramName: "file_xls",
                maxFilesize: 1000,
                acceptedFiles: ".xls,.xlsx",
                uploadMultiple: false,
                init: function () {
                    this.on("complete", function (response) {
                        $('#form-data').show();
                        $("#form-load").hide();
                        $('.btn-save').show();


                        var jsonResult = JSON.parse(response.xhr.response);
                        var selectOptions = makeSelectOptions(prepareColumnsToMakeOptions(jsonResult.columns));
                        $('.columns-select').html(selectOptions);
                        $('[name=file_name]').val(jsonResult.file);

                        setDefaultValues();
                    });
                },
                error: function (file, response) {
                    file.previewElement.classList.add("dz-error");
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        function makeSelectOptions(optionsArray, selectedId) {
            var options = '';
            $.map(optionsArray, function (item) {
                var selected = item.id == selectedId ? 'selected' : '';
                options += "<option value='" + item.id + "' " + selected + ">" + item.title + "</option>";
            });
            return options;
        }

        function prepareColumnsToMakeOptions(columns) {
            var result = $.map(columns, function (item) {
                return {
                    id: item,
                    title: item
                };
            });
            result.unshift({
                id: '',
                title: 'Не выбрано'
            });
            return result;
        }

        function setDefaultValues() {

            $('select[name="excel_columns[user_title]"]').val('fio');
            $('select[name="excel_columns[birth_date]"]').val('den_rozhdeniya');
            $('select[name="excel_columns[gender]"]').val('pol');

        }


        function sendForm() {

            if($('select[name="excel_columns[user_title]"]').val().length > 0 &&
                $('select[name="excel_columns[birth_date]"]').val().length > 0 &&
                $('select[name="excel_columns[gender]"]').val().length > 0)
            {

                $('#formXLS').submit();
                return true;
            }

        }

        String.prototype.replaceAll = function (search, replace) {
            return this.split(search).join(replace);
        }

    </script>


@stop
