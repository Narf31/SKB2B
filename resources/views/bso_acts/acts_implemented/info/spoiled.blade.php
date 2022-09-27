<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="filter-group">

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">Агент</label>
                    {{ Form::select('agent_id', \App\Models\User::getALLUserWhere()->get()->pluck('name', 'id'), session()->get('acts_implemented.agent_id')?:auth()->id(), ['class' => 'form-control select2', 'id'=>'agent_id', 'onchange'=>'loadContent()']) }}
                </div>

                <span class="btn btn-success btn-right" onclick="add_spoiled(0)">Испортить БСО</span>
                <span class="btn btn-primary btn-right" onclick="loadContent()">Применить</span>

            </div>
        </div>
    </div>
</div>

<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12 event_form" style="display:none;">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="filter-group">

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">Действие</label>
                    {{ Form::select('event_id', collect([1=>'Создать акт', 2=>'Добавить в акт']), 0, ['class' => 'form-control', 'id'=>'event_id', 'onchange'=>'get_realized_acts(2)']) }}
                </div>

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">№ акта</label>
                    {{ Form::select('order_id', collect([]), 0, ['class' => 'form-control', 'id'=>'order_id']) }}

                </div>


                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <span class="btn btn-success btn-right" onclick="create_act()">Выполнить</span>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="data_list" style="overflow: auto;">

</div>


<script>

    function initTab() {
        startMainFunctions();
        loadContent();
    }


    function loadContent(){

        var data = {
            agent_id: $('#agent_id').val(),
            kind_acceptance: $('#kind_acceptance').val(),
        };

        loaderShow();

        $.post("{{url("/bso_acts/acts_implemented/spoiled/list/")}}", {data:data}, function (response) {
            loaderHide();
            $("#data_list").html(response);

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });



    }


    function add_spoiled(bso_id) {
        openFancyBoxFrame('{{url("/bso_acts/acts_implemented/spoiled/edit/")}}?agent_id='+$('#agent_id').val()+"&bso_id="+bso_id);
    }



    function create_act() {

        var bso_array = [];
        $('.bso_item_checkbox:checked').each(function () {
            bso_array.push($(this).val());
        });
        var data = {
            user_id: $('#agent_id').val(),
            type_id: 4,
            bso_array: JSON.stringify(bso_array),
            order_id: $('#order_id').val()
        };

        loaderShow();

        $.post("{{url("/bso_acts/acts_implemented/spoiled/create_get_realized_acts/")}}", {data:data}, function (response) {
            loaderHide();
            show_actions();
            loadContent();

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });

    }



</script>
