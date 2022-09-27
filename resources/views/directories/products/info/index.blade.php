@extends('layouts.app')


@section('content')


    <div class="row col-xs-12 col-sm-12 col-md-6 col-lg-6">
        @include('directories.products.info.table', ['name'=>'Оформление', 'type'=>1, 'lists'=>$product->get_products_info(1)])
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-6 col-lg-6">
        @include('directories.products.info.table', ['name'=>'Убытки', 'type'=>2, 'lists'=>$product->get_products_info(2)])
    </div>




@endsection



@section('js')

    <script>


        $(function () {

            $('.sortable_table_columns').sortable({
                axis: 'y',
                update: function (event, ui) {
                    var data = $(this).sortable('serialize');


                    // POST to server using $.post or $.ajax
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: "{{ url("/directories/products/{$product->id}/edit/info/sort") }}?type="+$(this).data("type")
                    });
                }
            });

        });




    </script>


@endsection