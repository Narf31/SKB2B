@extends('layouts.app')

@section('content')

    <div class="col-md margin20 animated fadeInRight">
        <input type="hidden" name="overdue" value="0" onchange="loadItems()">
        <h2 class="inline-h1">
            <a href="/finance/debts">Финансовые долги</a> {{ $agent->name }}
        </h2>
        <a class="btn btn-success btn-right" id="detail_debts_export_xls">Экспорт в Excel</a>

        <br><br>

        <span class="legends">
            <a class="legend active" data-overdue="0" style="background-color: #DFD;">&nbsp;</a> все;
            <a class="legend" data-overdue="1">&nbsp;</a> претензий нет;
            <a class="legend" data-overdue="2" style="background-color: #DDF;">&nbsp;</a> более 3-х дней;
            <a class="legend" data-overdue="3" style="background-color: #FDD;">&nbsp;</a> более 15-ти дней;
        </span>

       <br><br>
       <div class="row form-group">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                <label class="control-label">Тип</label>
                {{ Form::select('type_ru', array_merge(['all' => 'Все'], \App\Models\Contracts\Payments::TYPE_RU), request('type_ru') ? request()->query('type_ru') : 'all', ['class' => 'form-control', 'id'=>'payment_id', 'required', 'onchange' => 'loadItems()']) }}
            </div>
           <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" >
               <label class="control-label">На странице</label>
               @php($status_select = collect([2=>'2(тестирование)', 25=>25, 50 => 50, 100=>100])->prepend('Все', ''))
               @php($status_selected = request('page_count') ? request()->query('page_count') : 25)
               {{ Form::select('page_count', $status_select, $status_selected, ['class' => 'form-control select2-all', 'id'=>'status', 'required', 'onchange' => 'loadItems()']) }}
           </div>
        </div>

        <div id="table"></div>
        <div id="page_list" style="display: inline;"></div>
        <center style="margin-top: 25px; margin-left: 48%; display: inline">
            <span id="view_row"></span>/<span id="max_row"></span>
        </center>
    </div>

@endsection


@section('js')

    <script>
        var PAGE = 1;

        $(function(){
            $(document).on('click', '[data-overdue]', function(){
                $('.legend').removeClass('active');
                $(this).addClass('active');
                var overdue = $(this).data('overdue');
                $('[name="overdue"]').val(overdue).change();
            });

            $(document).on('click', '#detail_debts_export_xls', function () {
                var data = getData();
                var method_param = {agent_id: '{{$agent->id}}'};
                var query = $.param({ method: 'Finance\\Debts\\DetailController@get_detail_table', param: data, method_param: method_param});
                location.href = '/exports/table2excel?'+query;
            });

            loadItems();
        });

        function getData(){
            return {
                overdue: $('[name="overdue"]').val(),
                type_ru: $('[name="type_ru"]').val(),
                PAGE: PAGE,
                page_count: $('[name="page_count"]').val()
            }
        }

        function setPage(field) {
            PAGE = field;
            loadItems();
        }
        function loadItems(){
            var data = getData();

            loaderShow();
            $.post("{{url("/finance/debts/{$agent->id}/get_detail_table")}}", data, function (response) {
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
            }).always(function() {
                loaderHide();
            });
        }

    </script>
@endsection

