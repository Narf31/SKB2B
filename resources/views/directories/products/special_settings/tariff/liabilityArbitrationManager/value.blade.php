<form id="product_form" class="product_form">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="row page-heading">
        <h2 class="inline-h1">{{$json['programs'][$request->program]['title']}}</h2>
    </div>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="row table">
            <thead>
            <tr>
                <th width="180px;" nowrap>{{$json['programs'][$request->program]['categorys_tab_title']}}</th>
                @foreach($json['programs'][$request->program]['categorys'][0] as $param1_id => $param1)
                    @if($param1_id > 0)
                    <th>{{$param1}}</th>
                    @endif
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($json['programs'][$request->program]['categorys'][1] as $param2_id => $param2)

                <tr>
                    <td nowrap><span class="hidden">{{$param2_id}}</span>{{$param2}}</td>
                    @foreach($json['programs'][$request->program]['categorys'][0] as $param1_id => $param1)
                        @if($param1_id > 0)
                        <td>
                            {{Form::text("value[{$param1_id}][{$param2_id}]",
                            ((isset($json_data['programs'][$request->program]))
                            ?\App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager::getTariffValue($json_data['programs'][$request->program]['values'], $param1_id, $param2_id)
                            :'')
                            , ['class' => 'sum', 'style'=>'width: 80px'])}}
                        </td>
                        @endif
                    @endforeach
                </tr>

            @endforeach
            </tbody>
        </table>


    </div>
    <br/>

</div>

<div class="clear"></div>
<span class="btn btn-success pull-left" onclick="saveTariff({{$request->program}})">Сохранить</span>

</form>





<script>

    _url = "{{$url}}";


    function saveTariff(id) {

        loaderShow();

        $.post(_url+"?program="+id, $('#product_form').serialize(), function (response) {

            if (Boolean(response.state) === true) {
                flashMessage('success', "Данные успешно сохранены!");

            }else {
                flashHeaderMessage(response.msg, 'danger');
            }

        }).always(function () {
            loaderHide();
        });

        return true;

    }


    function initActivTable()
    {


    }




    function remuveData(id) {
        $("#"+id).remove();
    }

</script>