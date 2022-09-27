@extends('layouts.app')

@section('head')
    <style>
        .payments_table td, .payments_table th {
            white-space: nowrap;
        }

        .filters_table td, .filters_table th {
            white-space: nowrap;
        }

        .content {
            overflow-x: scroll;
        }

    </style>
@endsection



@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h1 class="inline-h1">Аналитика - Общая</h1>
            <input type="submit" class="btn btn-success btn-right" value="Печать" name="print">
        </div>


        <form name="analitics_common">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="form-group">

                        <div class="col-sm-4">
                            <h2>Период</h2>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label"></label>
                                    {{ Form::select('payment_date_type_id', collect([1 => 'Даты оплаты', 2 => 'Дата договора', 3 => 'Дата по кассе']), 3, ['class' => 'form-control select2-all']) }}
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">С</label>
                                    {{ Form::text('date_from', '', ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">По</label>
                                    {{ Form::text('date_to', '', ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm-12">
                                <h2>Детали</h2>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Статус оплаты</label>
                                {{ Form::select('payment_status_id', collect([-1=>'Все', 0 => 'Не оплачен', 1 => 'Оплачен']), 1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Тип транзакции</label>
                                {{ Form::select('transaction_type', collect(\App\Models\Contracts\Payments::TRANSACTION_TYPE)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Условие продажи</label>
                                {{ Form::select('terms_sale', collect([-1=>'Все', 0 => 'Агентская продажа', 1 => 'Продажа организации', 2 => 'Продажа агента через организацию']), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Личная продажа</label>
                                {{ Form::select('personal_selling', collect([-1=>'Все', 0 => 'Нет', 1 => 'Да']), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Поток оплаты</label>
                                {{ Form::select('submit_receiver', collect([0 => 'Все', 1 => 'Брокер', 2 => 'СК']), 0, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Транзакции</label>
                                {{ Form::select('is_deleted', collect([-1=>'Все', 0 => 'Транзакции', 1 => 'Развязанные']), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Тип оплаты</label>
                                {{ Form::select('payment_type', collect(\App\Models\Contracts\Payments::TYPE_RU)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Пролонгация/новая</label>
                                {{ Form::select('type_id', collect([-1=>'Все', 0 => 'Новая', 1 => 'Пролонгация']), -1, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Количество</label>
                                {{ Form::select('page_count', collect([2=>2, 100=>'100', 200 => '200', 500 => '500', 0 => "Все"]), 100, ['class' => 'form-control select2-all']) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="block-sub-collapser" data-title="Основные"></div>
        </div>



        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="form-group">

                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label">СК</label>
                                    {{ Form::select('insurance_ids[]', $insurances->pluck('title', 'id'), 0, ['class' => 'form-control select2-all', 'multiple' => true]) }}
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Организация</label>
                                    {{ Form::select('org_ids[]', $organizations->pluck('title', 'id'), 0, ['class' => 'form-control select2-all', 'multiple' => true]) }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label">Подразделение</label>
                                    {{ Form::select('department_ids[]', $departments->pluck('title', 'id'), 0, ['class' => 'form-control select2-all', 'multiple' => true]) }}

                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Кассир</label>
                                    {{ Form::select('payment_user_id', $users->pluck('name', 'id')->prepend('Нет', 0), 0, ['class' => 'form-control select2-all']) }}

                                </div>
                            </div>
                        </div>


                        <div class="col-sm-8">
                            <div class="col-sm-4">
                                <label class="control-label">Продукт</label>
                                {{ Form::select('product_id[]', $products->pluck('title', 'id'), $products->first()->id, ['class' => 'form-control select2-all', 'multiple' => true]) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Регион</label>
                                {{ Form::select('filial_id', collect([0 => 'Не выбрано', 1 => 'ЦО']), 0, ['class' => 'form-control select2-all']) }}
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label">Локация</label>
                                {{ Form::select('region_id', collect([0 => 'Нет', 1 => 'Москва ЦО']), 0, ['class' => 'form-control select2-all']) }}
                            </div>

                            @php($finance_visibility = auth()->user()->role->visibility('finance'))
                            @if($finance_visibility && $finance_visibility != 2)
                                <div class="col-sm-4">
                                    <label class="control-label">Руководитель</label>
                                    {{ Form::select('nop_id', $users->pluck('name', 'id')->prepend('Нет', 0), 0, ['class' => 'form-control select2-all']) }}
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Агент</label>
                                    {{ Form::select('agent_id', $agents->pluck('name', 'id')->prepend('Нет', 0), 0, ['class' => 'form-control select2-all']) }}
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">Менеджер</label>
                                    {{ Form::select('manager_id', $users->pluck('name', 'id')->prepend('Нет', 0), 0, ['class' => 'form-control select2-all']) }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="block-sub-collapser" data-title="Дополнительно"></div>
        </div>

        </form>

        <div id="payments_table"></div>
        <div id="page_list"></div>
    </div>



@endsection



@section('js')

    <script>

        var PAGE = 1;

        $(function () {
            loadItems();

            $(document).on('change', 'form[name="analitics_common"]', function(){
                loadItems()
            });


            if ($('.sk_id_checkbox:checked').length == $('.sk_id_checkbox').length && $('.sk_id_checkbox:checked').length > 0) {
                $('.sk_id_select_all_chechbox').attr('checked', true);
            } else {
                $('.sk_id_select_all_chechbox').attr('checked', false);
            }
            if ($('.org_id_checkbox:checked').length == $('.org_id_checkbox').length && $('.org_id_checkbox:checked').length > 0) {
                $('.org_id_select_all_chechbox').attr('checked', true);
            } else {
                $('.org_id_select_all_chechbox').attr('checked', false);
            }

            $('.sale_channel_id_select_all_checkbox').attr('checked', $('.sale_channel_id_checkbox:checked').length == $('.sale_channel_id_checkbox').length && $('.sale_channel_id_checkbox:checked').length > 0);

        });




        function setPage(pageNumber) {
            PAGE = pageNumber;
            loadItems();
        }

        function loadItems(){
            loaderShow();
            var data = $('form[name="analitics_common"]').serialize() + "&PAGE=" + PAGE;

            $.get("{{url("/analitics/analitics_common/get_payments_table")}}", data, function (res) {
                $('#payments_table').html(res.html);
                $('#view_row').html(res.view_row);
                $('#max_row').html(res.max_row);
                $('#page_list').pagination({
                    total:res.page_max,
                    pageSize:1,
                    pageNumber: PAGE,
                    layout:['first','prev','links','next','last'],
                    onSelectPage: function(pageNumber, pageSize){
                        setPage(pageNumber);
                    }
                });

                loaderHide();
            }).always(function() { loaderHide(); });
        }



        function check_all_sk(obj) {
            $('.sk_id_checkbox').attr('checked', $(obj).is(':checked'));
        }

        function check_all_org(obj) {
            $('.org_id_checkbox').attr('checked', $(obj).is(':checked'));
        }

        function check_all_sale_channels(obj) {
            $('.sale_channel_id_checkbox').attr('checked', $(obj).is(':checked'));
        }
    </script>

@endsection