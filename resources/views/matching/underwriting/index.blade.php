@extends('layouts.app')




@section('content')

    @if(sizeof($result))
    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container"
         style="margin-top: -5px;overflow: auto;">
        <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="filter-group">





                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label">Категория</label>
                            {{ Form::select('category_id', \App\Models\Contracts\Matching::CATEGORY, null, ['class' => 'form-control select2-all',  'onchange'=>'loadData()',"multiple" => true]) }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="header_bab">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">
                    @foreach($result as $key => $count)
                        <div title="{{$count['title']}} {{($count['count']>0)?$count['count']:''}}" id="tab-{{$key}}" data-view="{{$key}}"></div>
                    @endforeach
                </div>

        </div>
    </div>


    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container"
         style="margin-top: -5px;overflow: auto;">
        <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="table" class="table"></div>
                </div>
            </div>
        </div>
    </div>

    @include('_chunks/_pagination',['class'=>'pull-right','callback'=>'loadData'])

    @else
        <h1>У вас нет доступных вкладок в этом разделе</h1>
    @endif


@stop

@section('js')

    <script>

        var TAB_INDEX = 0;
        var PAGE = 1;

        $(function () {

            if ($('[data-view]').length > 0) {
                $('#tt').tabs({
                    border: false,
                    pill: false,
                    plain: true,
                    onSelect: function (title, index) {
                        return selectTab(index);
                    }
                });
                selectTab(0);
            }
        });


        function selectTab(id) {

            TAB_INDEX = id;

            loaderShow();
            loadData();
            initTab();
            loaderHide();


        }

        function loadData() {

            $('#page_list').html('');
            $('#table_row').html('');
            $('#view_row').html(0);
            $('#max_row').html(0);

            $.post("{{url("/matching/underwriting/get-table")}}", getData(), function (response) {

                $('#table').html(response.html);
                $('#view_row').html(response.view_row);
                $('#max_row').html(response.max_row);

                ajaxPaginationUpdate(response.page_max, loadData);

            });


        }


        function getData() {

            var tab = $('#tt').tabs('getSelected');
            var load = tab.data('view');

            return {
                statys: load,
                category_id: $('[name="category_id"]').val(),
                page_count: $('[name="page_count"]').val(),
                PAGE: PAGE,
            }

        }

        function initTab() {
            startMainFunctions();

        }


        function setCheckUser(order_id) {


            var filesUrl = "/matching/underwriting/"+order_id+"/set-check-user";
            $.post(filesUrl, {}, function () {
                openPage("/matching/underwriting/"+order_id);
            });

        }


        function clearCheckUser(order_id) {

            var filesUrl = "/matching/underwriting/"+order_id+"/clear-check-user";
            $.post(filesUrl, {}, function () {
                //openPage("/contracts/orders/finance/"+order_id+"/contract/"+contract_id);
                loadData();

            });

        }


    </script>

@stop