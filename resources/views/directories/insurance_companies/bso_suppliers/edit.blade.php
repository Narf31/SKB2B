@extends('layouts.app')



@section('content')


    {{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/".(int)$bso_supplier->id."/"), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract', 'files' => true]) }}
    <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Филиал <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/")}}">{{$insurance_companies->title}}</a></h2>

            @if($bso_supplier->id>0)
                <span class="btn btn-info pull-right" onclick="openLogEvents('{{$bso_supplier->id}}', 16, 1)"><i class="fa fa-history"></i> </span>
            @endif

        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Актуально</label>
                        <div class="col-sm-8">
                            {{ Form::checkbox('is_actual', 1, $bso_supplier->is_actual) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Название</label>
                        <div class="col-sm-6">
                            {{ Form::text('title', $bso_supplier->title, ['class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-1">
                            <span style="font-size: 18px;cursor: pointer;" onclick="generateName()">
                                <i class="fa fa-cogs"></i>
                            </span>
                        </div>
                    </div>



                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Источник</label>
                        <div class="col-sm-8">
                            {{ Form::select('source_org_id', App\Models\Organizations\Organization::getOrgProvider()->get()->pluck('title', 'id')->prepend('Страховая компания', 0), $bso_supplier->source_org_id, ['class' => 'source_org_id form-control select2-ws', 'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Цель</label>
                        <div class="col-sm-8">
                            {{ Form::select('purpose_org_id', App\Models\Organizations\Organization::getOrgProvider()->get()->pluck('title', 'id'), $bso_supplier->purpose_org_id, ['class' => 'purpose_org_id form-control select2-ws', 'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Город</label>
                        <div class="col-sm-8">
                            {{ Form::select('city_id', \App\Models\Settings\City::where('is_actual', '=', '1')->get()->pluck('title', 'id'), $bso_supplier->city_id, ['class' => 'form-control select2-ws', 'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-4 control-label">Подписант</label>
                        <div class="col-sm-8">
                            {{ Form::text('signer', $bso_supplier->signer, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <input class="pull-right btn btn-primary" type="submit" value="Сохранить"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}

    @if(isset($bso_supplier) && $bso_supplier->id > 0)

        @include('directories.insurance_companies.partials.hold_kv', ['insurance_companies' => $insurance_companies, 'bso_supplier' => $bso_supplier])

        <div class="row"></div>

        @include('directories.insurance_companies.partials.financial_policy', ['insurance_companies' => $insurance_companies, 'bso_supplier' => $bso_supplier])


    @endif


@stop

@section('js')

<script>


    function generateName() {

        var sk_title = "{{$insurance_companies->title}}";
        if (sk_title.indexOf("-") + 1) {
            sk_title = sk_title.substr(0, sk_title.indexOf("-"));
        }
        sk_title = sk_title.replace(/\ /gi, "");

        var source_title = $(".source_org_id :selected").text();
        if (source_title == "Страховая компания") {
            source_title = "";
        }
        else {
            source_title = "-" + source_title;
        }

        var target_title = $(".purpose_org_id :selected").text();
        target_title = target_title.replace(/\"/gi, "");
        target_title = target_title.replace(/ООО/gi, "");
        target_title = target_title.replace(/\ /gi, "");
        if (target_title !== "") {
            target_title = "-" + target_title;
        }

        var region_title = $("select[name=city_id] :selected").html();
        if (region_title.indexOf(" ") + 1) {
            region_title = region_title.substr(0, region_title.indexOf(" "));
        }
        region_title = region_title.replace(/\ /gi, "");
        if (region_title !== "") {
            region_title = " " + region_title;
        }

        var result_value = "";
        result_value = sk_title + source_title + target_title + region_title;
        result_value = $('<textarea/>').html(result_value).text();
        $("form input[name=title]").val(result_value);


    }

</script>

@stop