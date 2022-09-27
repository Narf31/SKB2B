@extends('layouts.app')

@section('content')
    <div class="col-md margin20 animated fadeInRight">
        <input type="hidden" name="overdue" value="0" onchange="loadItems()">
        <h2 class="inline-h1">Финансовые долги</h2>
        <a class="btn btn-success btn-right" style="display: inline-block;" id="agent_debts_export_xls">Экспорт в Excel</a>

        <br><br>
        <span class="legends">
            <a class="legend active" data-overdue="0" style="background-color: #DFD;">&nbsp;</a> все;
            <a class="legend" data-overdue="1">&nbsp;</a> претензий нет;
            <a class="legend" data-overdue="2" style="background-color: #DDF;">&nbsp;</a> более 3-х дней;
            <a class="legend" data-overdue="3" style="background-color: #FDD;">&nbsp;</a> более 15-ти дней;
        </span>

        <br><br>
        <div class="row form-group">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <label class="control-label">Агент</label>
                {{ Form::select('agent_id', auth()->user()->visibleAgents('finance')->get()->pluck('name', 'id')->prepend('Нет', 0), request('agent_id') ? request()->query('agent_id') : 0, ['class' => 'form-control select2 select2-all', 'id'=>'agent_id', 'required', 'onchange' => 'loadItems()']) }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                <label class="control-label">Руководитель</label>
                {{ Form::select('parent_agent_id', \App\Models\User::all()->pluck('name', 'id')->prepend('Нет', 0), request('parent_agent_id') ? request()->query('parent_agent_id') : 0, ['class' => 'form-control select2 select2-all', 'id'=>'nop_id', 'required', 'onchange' => 'loadItems()']) }}
            </div>
        </div>
        
        <div id="table"></div>

    </div>

@endsection


@section('js')

    <script>
        $(function(){
            $(document).on('click', '[data-overdue]', function(){
                $('.legend').removeClass('active');
                $(this).addClass('active');
                var overdue = $(this).data('overdue');
                $('[name="overdue"]').val(overdue).change();
            });

            $(document).on('click', '#agent_debts_export_xls', function () {
                var data = getData();
                var query = $.param({ method: 'Finance\\Debts\\AgentController@get_agent_table', param: data});
                location.href = '/exports/table2excel?'+query;
            });

            loadItems();
        });


        function getData(){
            return {
                overdue: $('[name="overdue"]').val(),
                agent_id: $('[name="agent_id"]').val(),
                parent_agent_id: $('[name="parent_agent_id"]').val(),
            };
        }

        function loadItems(){
            var data = getData();

            loaderShow();
            $.post("{{url("/finance/debts/get_agent_table")}}", data, function (response) {

                $('#table').html(response.html);
                $(document).on('click', '.clickable-row', function(){
                    var link = $(this).data('href');
                    if(link){
                        location.href = link;
                    }
                })
            }).always(function() {
                loaderHide();
            });
        }



    </script>
@endsection

