<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-group">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <label class="control-label">Агент</label>
            @php($agent_select = \App\Models\Characters\Agent::all()->pluck('name','id')->prepend('Все', 0))
            @php($agent_selected = request('agent_id') ? request()->query('agent_id') : auth()->user()->id)
            {{ Form::select('agent_id', $agent_select, $agent_selected, ['class' => 'form-control select2-all', 'id'=>'agent_id', 'required', 'onchange' => 'loadItems()']) }}
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <label class="control-label">Тип</label>
            @php($type_select = collect(['cash'=>'Наличные', 'cashless'=>'Безналичные', 'sk' =>'СК'])->prepend('Все', ''))
            @php($type_selected = request('type') ? request()->query('type') : 0)
            {{ Form::select('type', $type_select, $type_selected, ['class' => 'form-control select2-all', 'id'=>'type', 'required', 'onchange' => 'loadItems()']) }}
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label">Статус</label>
            @php($status_select = collect(\App\Models\Finance\Invoice::STATUSES)->prepend('Все', ''))
            @php($status_selected = request('status_id') ? request()->query('status_id') : 1)
            {{ Form::select('status_id', $status_select, $status_selected, ['class' => 'form-control select2-all', 'id'=>'status_id', 'required', 'onchange' => 'loadItems()']) }}
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" >
            <label class="control-label">На странице</label>
            @php($page_count_select = collect([2=>'2(тестирование)', 25=>25, 50 => 50, 100=>100, 0=>'Все']))
            @php($page_count_selected = request('page_count') ? request()->query('page_count') : 25)
            {{ Form::select('page_count', $page_count_select, $page_count_selected, ['class' => 'form-control select2-all', 'id'=>'page_count', 'required', 'onchange' => 'loadItems()']) }}
        </div>

    </div>
</div>

<div id="table"></div>

<div class="row">

    <center style="margin-top: 25px; margin-left: 48%; display: inline-block">
        <span id="view_row"></span>/<span id="max_row"></span>
    </center>
    <div id="page_list" class="easyui-pagination pull-right"></div>
</div>


<script>

    var PAGE = 1;


    $(function () {
        $(document).on('click', '[name="all_payments"]', function(){
            var checked = $(this).prop('checked');
            $('[name*="payment["]').prop('checked', checked).change();
        });

        $(document).on('click', '[name*="payment["]', function(){
            var all_checked = true;
            $.each($('[name*="payment["]'), function(k,v){
                all_checked = all_checked && $(v).prop('checked');
            });
            $('[name="all_payments"]').prop('checked', all_checked).change();
        });


        loadItems();
    });

    function setPage(field) {
        PAGE = field;
        loadItems();
    }

    function loadItems(){
        loaderShow();

        var data = getData();

        $('#page_list').html('');
        $('#table_row').html('');
        $('#view_row').html(0);
        $('#max_row').html(0);

        $.post("{{url("/finance/invoice/invoices/get_invoices_table")}}", data, function (response) {

            $('#table').html(response.html);
            $('#view_row').html(response.view_row);
            $('#max_row').html(response.max_row);

            $('#page_list').pagination({
                total:response.page_max,
                pageSize:1,
                pageNumber: PAGE,
                layout:['first','prev','links','next','last'],
                onSelectPage: function(pageNumber, pageSize){
                    setPage(pageNumber);
                }
            });


            $(document).on('click', '.clickable-row', function(){
                location.href = $(this).data('href');
            })

        }).fail(function(){
            $('#table').html('');
        }).always(function() {
            loaderHide();
        });
    }

    function getData(){
        return {
            type: $('[name="type"]').val(),
            status_id: $('[name="status_id"]').val(),
            page_count: $('[name="page_count"]').val(),
            agent_id: $('[name="agent_id"]').val(),

            PAGE: PAGE,
        }
    }

</script>