<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-group">
        @if(auth()->user()->role->rolesVisibility(7)->visibility != 2)
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <label class="control-label">Агент</label>
                @php($agents_select = auth()->user()->visibleAgents('finance')->get()->pluck('name', 'id'))
                @php($agent_selected = request('agent_id') ? request()->query('agent_id') : auth()->user()->id)
                {{ Form::select('agent_id', $agents_select, $agent_selected, ['class' => 'form-control select2 select2-all', 'id'=>'agent_id', 'required', 'onchange' => 'loadItems()']) }}
            </div>
        @else
            {{Form::hidden('agent_id', auth()->user()->id)}}
        @endif

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label">Тип</label>
            @php($type_select = collect(['cash'=>'Наличные', 'cashless'=>'Безналичные', 'sk' =>'СК'])->prepend('Все', ''))
            @php($type_selected = request('type') ? request()->query('type') : 0)
            {{ Form::select('type', $type_select, $type_selected, ['class' => 'form-control select2 select2-all', 'id'=>'type', 'required', 'onchange' => 'loadItems()']) }}
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <label class="control-label">Страховая компания</label>
            @php($insurance_select = \App\Models\Directories\InsuranceCompanies::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('Все', -1))
            @php($insurance_selected = request('insurance_companies_id') ? request()->query('insurance_companies_id') : '')
            {{ Form::select('insurance_companies_id', $insurance_select , $insurance_selected, ['class' => 'form-control select2-ws', 'id'=>'insurance_companies_id', 'onchange'=>'loadItems()']) }}
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" >
            <label class="control-label">На странице</label>
            @php($status_select = collect([2=>'2(тестирование)', 25=>25, 50 => 50, 100=>100, 0=>'Все']))
            @php($status_selected = request('page_count') ? request()->query('page_count') : 25)
            {{ Form::select('page_count', $status_select, $status_selected, ['class' => 'form-control select2-all', 'id'=>'status', 'required', 'onchange' => 'loadItems()']) }}
        </div>
        
    </div>
</div>

<div id="table">

</div>



<a href="#" class="btn btn-success btn-right" id="add_to_invoice" style="display: none;">Создать счёт</a>
<a href="#" class="btn btn-primary btn-right" id="invoice_update" style="display: none;">Добавить к счету</a>


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

        $(document).on('click', '[name*="payment"]', function(){
            if($('[name*="payment["]:checked').length>0){
                $('#add_to_invoice').show();
                $('#invoice_update').show();
            }else{
                $('#add_to_invoice').hide();
                $('#invoice_update').hide();
            }
        });

        $(document).on('click', '#add_to_invoice', function(){
            var payments = $('[name*="payment["]:checked');

            var data = {};
            $.each(payments, function(k,v){
                 data['payments['+k+']'] = $(v).val();
            });
            var query = build_query(data);
            $.get("/finance/invoice/invoices/create?"+query, {}, function(res){
                if(res.status === 'ok'){
                    openFancyBoxFrame("/finance/invoice/invoices/create?"+query);
                }else{
                    flashMessage('danger',res.error)
                }
            }, 'json');
        });

        $(document).on('click', '#invoice_update', function(){
            var payments = $('[name*="payment["]:checked');
            var data = {};
            $.each(payments, function(k,v){
                 data['payments['+k+']'] = $(v).val();
            });
            var query = build_query(data);

            $.get("/finance/invoice/payments/update_invoice?"+query, {}, function(res){
                if(res.status === 'ok'){
                    openFancyBoxFrame("/finance/invoice/payments/update_invoice?"+query);
                }else{
                    flashMessage('danger',res.error)
                }
            }, 'json');
        });

        loadItems();

    });


    function loadItems(){
        loaderShow();

        var data = getData();

        $('#page_list').html('');
        $('#table_row').html('');
        $('#view_row').html(0);
        $('#max_row').html(0);


        $.post("{{url("/finance/invoice/payments/get_payments_table")}}", data, function (response) {
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
        }).fail(function(){
            $('#table').html('');
        }).always(function() {
            loaderHide();
        });

    }
    function getData(){
        return {
            agent_id: $('[name="agent_id"]').val(),
            type: $('[name="type"]').val(),
            page_count: $("#page_count").val(),
            page: PAGE,
            insurance_companies_id: $('[name="insurance_companies_id"]').val(),
        }
    }


</script>