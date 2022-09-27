
<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="filter-group">

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">Точка продаж</label>


                    {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::getPointsSaleAll()->get()->pluck('title', 'id'), session()->get('acts_transfer_tp.point_sale_id')?:auth()->user()->point_sale_id, ['class' => 'form-control select2-ws', 'id'=>'point_sale_id', 'onchange'=>'loadContent()']) }}


                </div>

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label">Продукт</label>
                    {{ Form::select('product_id', \App\Models\Directories\Products::all()->pluck('title', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2-ws']) }}
                </div>


                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Номер договора</label>
                    {{ Form::text('contract_bso_title', '', ['class' => 'form-control']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Страхователь</label>
                    {{ Form::text('contract_insurer', '', ['class' => 'form-control']) }}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="btn btn-primary btn-left" onclick="loadContent()">Применить</span>

                    <div class="btn-right" style="margin-top: 12px;margin-left: 50%; display: inline-block">
                        <span id="view_row">0</span>/<span id="max_row">0</span>
                    </div>

                </div>



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

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3" style="display: none;" id="div-order_id">
                    <label class="control-label" for="user_id_from">№ акта</label>
                    {{ Form::select('order_id', collect([]), 0, ['class' => 'form-control', 'id'=>'order_id']) }}
                </div>

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3" id="div-tp_id">
                    <label class="control-label" for="user_id_from">Точка продаж</label>
                    {{ Form::select('new_tp_id', \App\Models\Settings\PointsSale::all()->pluck('title', 'id'), 0, ['class' => 'form-control', 'id'=>'new_tp_id']) }}
                </div>


                <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="btn btn-success btn-left" onclick="create_act()">Выполнить</span>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="data_list" style="overflow: auto;">
    <div id="table_info"></div>
    <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
</div>



<script>

    var PAGE = 1;

    function initTab() {
        startMainFunctions();
        loadContent();
    }


    function loadContent(){

        var data = {
            PAGE: PAGE,
            point_sale_id: $('#point_sale_id').val(),
            product_id: $('[name="product_id"]').val(),
            contract_insurer: $('[name="contract_insurer"]').val(),
            contract_bso_title: $('[name="contract_bso_title"]').val(),
        };

        activePagination(0, 0, 1);

        $('#table_info').html('');

        loaderShow();

        $.post("{{url("/bso_acts/acts_transfer_tp/contract/list/")}}", data, function (response) {
            loaderHide();

            activePagination(response.view_row, response.max_row, response.page_max);
            $('#table_info').html(response.html);

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });



    }

    function activePagination(view_row, max_row, pages) {

        $('#view_row').html(view_row);
        $('#max_row').html(max_row);

        $('#page_list').pagination({
            total: pages,
            pageSize: 1,
            pageNumber: PAGE,
            layout: ['first', 'prev', 'links', 'next', 'last'],
            onSelectPage: function (pageNumber, pageSize) {
                PAGE = pageNumber;
                loadItems()
            },

        });
    }



    function create_act() {

        var bso_item_array = [];
        $('.bso_item_checkbox:checked').each(function () {
            bso_item_array.push($(this).val());
        });
        var data = {
            point_sale_id: $('#point_sale_id').val(),
            type_id: 2,
            bso_item_array: JSON.stringify(bso_item_array),
            order_id: $('#order_id').val(),
            new_tp_id: $('#new_tp_id').val(),
            event_id: $('#event_id').val(),

        };

        loaderShow();

        $.post("{{url("/bso_acts/acts_transfer_tp/contract/create_get_realized_acts/")}}", data, function (response) {
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
