@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Настройка продуктов <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}")}}">{{$bso_supplier->title}}</a></h2>

        </div>

        <div class="block-main">
            <div class="block-sub">

                {{ Form::model($hold_kv, ['url' => url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/"), 'method' => 'put', 'class' => 'form-horizontal']) }}


                @include('directories.insurance_companies.bso_suppliers.hold_kv.form')

                <div class="form-group">
                    <label class="col-sm-4 control-label">Вид БСО</label>
                    <div class="col-sm-8">
                        {{ Form::select('bso_class_id', collect([-1=>'Любой', 0=>'Бумажный', 1=>'Электронный']), $hold_kv->bso_class_id, ['class' => 'form-control', 'required', 'id'=>'bso_class_id_main', "onchange"=>"changeBsoClass()"]) }}
                    </div>
                </div>

                <div class="form-group hidden">

                    <label class="col-sm-3 control-label" >Много файлов</label>
                    <div class="col-sm-1">
                        {{ Form::checkbox('is_many_files', 1, $hold_kv->is_many_files, ['id'=>"is_many_files", "onchange"=>"setManyFiles()"]) }}
                    </div>



                    <div class="hidden">
                        <label class="col-sm-3 control-label">Проверять договор</label>
                        <div class="col-sm-1">
                            {{ Form::checkbox('is_check_policy', 1, $hold_kv->is_check_policy, ['class' => 'form-control']) }}
                        </div>
                    </div>


                </div>

                <div class="form-group">

                    <div class="col-sm-12" >
                        {{ Form::text('many_text', $hold_kv->many_text, ['class' => 'form-control', 'id'=>'many_text', "placeholder"=>"Перечень документов"]) }}
                    </div>
                </div>

                <div class="row form-group">
                    <table id="tab_files" class="tov-table">
                        <tr>
                            <td><center><strong>Документ</strong></center></td>
                        {{--<td><center><strong>Название для api</strong></center></td>--}}
                        <td><center><strong>Обязателен</strong></center></td>
                        <td><center><strong>Применимость</strong></center></td>
                        <td><span class="btn btn-success pull-right" onclick="addFormFiles()"><i class="fa fa-plus"></i> Добавить</span></td>
                        </tr>

                        @if(sizeof($hold_kv->documents))

                        @foreach($hold_kv->documents as $key => $document)
                        <tr id='file_tr_{{$key}}'>
                            <td class="vertical-align-middle"><input id='file_title_{{$key}}' name='file_title[]' value='{{$document->file_title}}' class='form-control' required type='text'>{{--</td>
                            <td>--}}<input id='file_name_{{$key}}' name='file_name[]' value='{{$document->file_name}}' class='form-control' type='hidden'></td>
                            <td class="vertical-align-middle">
                                {{ Form::select('is_required[]', collect([1=>'Да', 0=>'Нет']), $document->is_required, ['class' => 'form-control', 'id'=>"is_required_{$key}"]) }}
                            </td>
                            <td class="vertical-align-middle">
                            {{ Form::select('program_id[]', isset($hold_kv->product->programs) ? $hold_kv->product->programs->where('slug','!=','calculator')->pluck('title','id')->prepend('Для всех', '0') : collect([0=>'Для всех']), $document->program_id, ['class' => 'form-control', 'id'=>"program_id_{$key}"]) }}
                            </td>
                            <td class="vertical-align-middle"><span class='btn btn-primary pull-right' onclick='delFormFiles({{$key}})'><i class='fa fa-minus'></i> Удалить</span></td>
                        </tr>
                        @endforeach

                        @endif

                    </table>
                </div>

                <input type="submit" class="btn btn-primary" value="Сохранить"/>


                {{Form::close()}}
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Вид оплаты <span id="product_title">{{\App\Models\Directories\Products::find($hold_kv->product_id)->title}}</span></h2>
        </div>

        <div class="block-main">
            <div class="block-sub">

                <form id="group_data_info">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" >Группа</label>
                        <div class="col-sm-8">
                            {{ Form::select('group_id', \App\Models\Settings\FinancialGroup::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('По умолчанию', 0), 0, ['class' => 'form-control', 'id'=>'group_id', "onchange"=>"getPaymentInfo()"]) }}
                        </div>
                    </div>

                    <div class="form-group" id="bso_class_field" {{($hold_kv->bso_class_id == -1 ? "" : "hidden")}}>
                        <label class="col-sm-4 control-label">Вид БСО</label>
                        <div class="col-sm-8">
                            {{ Form::select('bso_class_id', collect([-1=>'Любой', 0=>'Бумажный', 1=>'Электронный']), $hold_kv->bso_class_id , ['class' => 'form-control', 'required', 'id'=>'bso_class_id', "onchange"=>"getPaymentInfo()"]) }}
                        </div>
                    </div>
                    <div class="clear" style="border-bottom: solid 1px #e0e0e0;"></div>
                    <div id="group_info">
                    </div>

                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <a href="javascript:void(0);" class="btn btn-success pull-left" onclick="savePaymentInfo()">Сохранить</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Дополнительная информация по группе: <span id="group_title">По умолчанию</span></h2>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Алгоритмы рассрочки</h2>
            <span class="btn btn-success pull-right" onclick="addInstallmentAlgorithmsPayment(0)">Добавить</span>
        </div>
        <div class="block-main">
            <div class="block-sub">

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="installment_algorithms_info">
                </div>


            </div>
        </div>

        <div class="page-subheading">
            <h2>Осмотр</h2>
            <span class="btn btn-success pull-right" onclick="addMatchingTerms('inspection', 0)">Добавить</span>
        </div>
        <div class="block-main">
            <div class="block-sub">

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="matching-terms-inspection">
                </div>

            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Согласования андеррайтинг</h2>
            <span class="btn btn-success pull-right" onclick="addMatchingTerms('underwriter', 0)">Добавить</span>
        </div>
        <div class="block-main">
            <div class="block-sub">

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="matching-terms-underwriter">
                </div>


            </div>
        </div>

        <div class="page-subheading">
            <h2>Согласования службы безопасности</h2>
            <span class="btn btn-success pull-right" onclick="addMatchingTerms('sb', 0)">Добавить</span>
        </div>
        <div class="block-main">
            <div class="block-sub">

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="matching-terms-sb">
                </div>

            </div>
        </div>
    </div>



</div>


@stop



@section('js')

<script>

    $(function () {



        setManyFiles()

        getPaymentInfo();

    });
    function setManyFiles()
    {

        if ($("#is_many_files").is(':checked')){
        $("#many_text").show();
        } else{
        $("#many_text").hide();
        }

    }

    var MY_COUNT_FILES = "{{ ($hold_kv->documents)?count($hold_kv->documents):0}}";
    function addFormFiles(){
        var options = '';
        var data = {!! isset($hold_kv->product->programs) ? $hold_kv->product->programs->where('slug','!=','calculator')->pluck('title','id') : [] !!};
        Object.keys(data).forEach(function(key) {
            options += '<option value="'+key+'">'+this[key]+'</option>';
        }, data);


        tr = "<tr id='file_tr_" + MY_COUNT_FILES + "'>" +
                "<td class='vertical-align-middle'><input id='file_title_" + MY_COUNT_FILES + "' name='file_title[]' value='' class='form-control' required type='text'>" +
                "<input id='file_name_" + MY_COUNT_FILES + "' name='file_name[]' value='' class='form-control' type='hidden'></td>" +
                "<td class='vertical-align-middle'><select class=\"form-control\" id=\"is_required_" + MY_COUNT_FILES + "\" name=\"is_required[]\"><option value=\"1\" selected=\"selected\">Да</option><option value=\"0\">Нет</option></select></td>" +
                "<td><select class=\"form-control\" id=\"is_required_" + MY_COUNT_FILES + "\" name=\"program_id[]\"><option value=\"0\" selected=\"selected\">Для всех</option>"+options+"</select></td>" +
                "<td class='vertical-align-middle'><span class='btn btn-primary pull-right' onclick='delFormFiles(" + MY_COUNT_FILES + ")'><i class='fa fa-minus'></i>Удалить</span></td>" +
                "</tr>";
        $('#tab_files tr:last').after(tr);
        MY_COUNT_FILES = parseInt(MY_COUNT_FILES) + 1;
    }

    function delFormFiles(id){
        $('#file_tr_' + id).remove();
    }

    function changeBsoClass(){
        bso_class_id = $("#bso_class_id_main").val();
        $("#bso_class_id").val(bso_class_id);
        if(bso_class_id == -1){
            $("#bso_class_field").removeAttr("hidden");
        }else{
            $("#bso_class_field").attr("hidden","hidden");
            deleteOtherPaymentInfo(bso_class_id);
        }

        getPaymentInfo();
        $('.form-horizontal').submit();
    }

    function getPaymentInfo() {
        group_id = $("#group_id").val();
        $("#group_title").text($("#group_id option:selected").text());
        bso_class_id = $("#bso_class_id").val();
        $("#group_info").html(myGetAjax("{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/group_info")}}?group_id="+group_id+"&bso_class_id="+bso_class_id));

        getInstallmentAlgorithmsPayment();

        getMatchingTerms('underwriter');
        getMatchingTerms('sb');
        getMatchingTerms('inspection');
    }

    function savePaymentInfo() {
        url = "{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/group_save")}}";

        loaderShow();

        $.post(url, $('#group_data_info').serialize(), function (response) {


        }).always(function () {
            loaderHide();
        });


    }

    function deleteOtherPaymentInfo(bso_class_id) {
        url = "{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/group_delete")}}";

        loaderShow();

        $.post(url, {bso_class_id: bso_class_id}, function (response) {


        }).always(function () {
            loaderHide();
        });


    }

    function getInstallmentAlgorithmsPayment()
    {
        group_id = $("#group_id").val();
        $("#installment_algorithms_info").html(myGetAjax("{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/installment_algorithms_info")}}?group_id="+group_id));
    }


    function addInstallmentAlgorithmsPayment(algorithms_id)
    {
        urls = '/directories/insurance_companies/{{$hold_kv->insurance_companies_id}}/bso_suppliers/{{$hold_kv->bso_supplier_id}}/hold_kv/{{$hold_kv->id}}/installment_algorithms_payment/'+$("#group_id").val()+'/'+algorithms_id;

        openFancyBoxFrame(urls);


    }



    function getMatchingTerms(type)
    {
        group_id = $("#group_id").val();

        ur = "{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/hold_kv/{$hold_kv->id}/matching-terms")}}?type="+type+"&group_id="+group_id;

        $("#matching-terms-"+type).html(myGetAjax(ur));
    }

    function addMatchingTerms(type, matching_id)
    {
        group_id = $("#group_id").val();
        f_open = "/directories/insurance_companies/{{$insurance_companies->id}}/bso_suppliers/{{$bso_supplier->id}}/hold_kv/{{$hold_kv->id}}/matching-terms/"+group_id+"/"+type+"/"+matching_id;

        openFancyBoxFrame(f_open);

    }




</script>

@stop