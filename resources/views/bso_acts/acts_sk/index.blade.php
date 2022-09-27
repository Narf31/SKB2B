@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h1 class="inline-h1">Акты в СК</h1>
    </div>
    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="filter-group" id="filters"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="row">
            <div id="table"></div>
        </div>

    </div>


@stop

@section('js')

    <script>
        $(function () {
            loadItems()
        });


        function getData(){
            return {
                org_id:$('[name="org_id"]').val(),
                insurance_id:$('[name="insurance_id"]').val(),
                supplier_id:$('[name="supplier_id"]').val(),
            }
        }


        function loadItems(){
            loaderShow();

            $.post('/bso_acts/acts_sk/get_filters', getData(), function(filter_res){
                $('#filters').html(filter_res);
                $.post('/bso_acts/acts_sk/get_table', getData(), function(table_res){
                    $('#table').html(table_res.html);
                    $('.select2-ws').select2("destroy").select2({
                        width: '100%',
                        dropdownCssClass: "bigdrop",
                        dropdownAutoWidth: true,
                        minimumResultsForSearch: -1
                    });
                });

            }).always(function(){
                loaderHide();
            });




        }

    </script>


@stop