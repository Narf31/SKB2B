
<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="filter-group">

                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">Точка продаж</label>
                    {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::getPointsSaleAll()->get()->pluck('title', 'id'), session()->get('acts_transfer_tp.point_sale_id')?:auth()->user()->point_sale_id, ['class' => 'form-control select2-ws', 'id'=>'point_sale_id', 'onchange'=>'loadContent()']) }}

                </div>
                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <label class="control-label" for="user_id_from">Номер акта</label>
                     {{ Form::text('number', '', ['class' => 'form-control', 'id' => 'number']) }}
                </div>

                <span class="btn btn-primary btn-right" onclick="loadContent()">Применить</span>

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
            point_sale_id: $('#point_sale_id').val(),
            number: $('#number').val(),
        };

        loaderShow();

        $.post("{{url("/bso_acts/acts_transfer_tp/acts/list/")}}", {data:data}, function (response) {
            loaderHide();
            $("#data_list").html(response);

            $(".clickable-row").click( function(){
                if ($(this).attr('data-href')) {
                    window.location = $(this).attr('data-href');
                }
            });


        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });



    }








</script>