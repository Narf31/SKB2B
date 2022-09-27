@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h1 class="inline-h1">Алгоритмы рассрочки</h1>
    </div>

    {{ Form::open(['url' => url("/settings/installment_algorithms_payment/".(int)$algorithm->id."/edit"), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="row form-group">


                        <div class="form-group">
                            <label class="col-sm-4 control-label">Названия</label>
                            <div class="col-sm-8">
                                {{ Form::text('title', $algorithm->title, ['class' => 'form-control', 'required']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">По умолчанию</label>
                            <div class="col-sm-8">
                                {{ Form::checkbox('is_default', 1, (int)$algorithm->is_default) }}
                            </div>
                        </div>


                        <table id="tab_algorithms">
                            <tr>
                                <td class="vertical-align-middle pb-10 w-150px"><center><strong>Процент</strong></center></td>
                                <td class="vertical-align-middle pb-10 w-150px"><center><strong>Месяц начиная с 0</strong></center></td>
                                <td class="vertical-align-middle pb-10 w-150px"><span class="btn btn-success pull-right" onclick="addFormFiles()"><i class="fa fa-plus"></i>Добавить</span></td>
                            </tr>

                            @if(sizeof($algorithm->algorithm_list))

                                @foreach($algorithm->algorithm_list as $key => $list)
                                    <tr id='algorithms_tr_{{$key}}'>
                                        <td class="vertical-align-middle pb-10 w-150px"><input id='algorithms_payment_{{$key}}' name='algorithms_payment[]' value='{{$list->payment}}' class='form-control sum' required type='text'></td>
                                        <td class="vertical-align-middle pb-10 w-150px"><input id='algorithms_month_{{$key}}' name='algorithms_month[]' value='{{$list->month}}' class='form-control' type='text'></td>
                                        <td class="vertical-align-middle pb-10 w-150px"><span class='btn btn-danger pull-right' onclick='delFormAlgorithms({{$key}})'><i class='fa fa-minus'></i>Удалить</span></td>
                                    </tr>
                                @endforeach

                            @endif


                        </table>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary pull-left" value="Сохранить"/>


                @if((int)$algorithm->id > 0)
                    <span class="btn btn-danger pull-right" onclick="deleteAlgorithm('/settings/installment_algorithms_payment/{{$algorithm->id}}/delete', '')">Удалить</span>

                @endif

            </div>
        </div>
    </div>

    {{Form::close()}}





@endsection

@section('js')

    <script>

        $(function () {



        });


        var MY_COUNT_FILES = "{{ ($algorithm->algorithm_list)?count($algorithm->algorithm_list):0}}";
        function addFormFiles(){

            tr = "<tr id='algorithms_tr_" + MY_COUNT_FILES + "'>" +
                "<td class='vertical-align-middle pb-10'><input id='algorithms_payment_" + MY_COUNT_FILES + "' name='algorithms_payment[]' value='' class='form-control sum' required type='text'></td>" +
                "<td class='vertical-align-middle pb-10'><input id='algorithms_month_" + MY_COUNT_FILES + "' name='algorithms_month[]' value='' class='form-control' type='text'></td>" +
                "<td class='vertical-align-middle pb-10'><span class='btn btn-danger pull-right' onclick='delFormAlgorithms(" + MY_COUNT_FILES + ")'><i class='fa fa-minus'></i>Удалить</span></td>" +
                "</tr>";
            $('#tab_algorithms tr:last').after(tr);
            MY_COUNT_FILES = parseInt(MY_COUNT_FILES) + 1;
        }

        function delFormAlgorithms(id){
            $('#algorithms_tr_' + id).remove();
        }


        function deleteAlgorithm(url, id) {
            if (!customConfirm()) return false;

            $.post('{{url('/')}}' + url + id, {
                _method: 'delete'
            }, function () {
                openPage('{{url ("/settings/installment_algorithms_payment/")}}')
            });
        }


    </script>

@stop