<div class="row form-horizontal">

    <div class="col-md-6 col-lg-3">
        <label class="control-label">Категория <span class="required">*</span></label>
        {{Form::select("category", collect(\App\Processes\Operations\Contracts\Settings\Kasco\Coefficients::CATEGORY_A), 'terms', ['class' => 'form-control select2-ws', "id" => "category", 'onchange'=>"viewCategory();"])}}
    </div>
    <div class="clear"></div>

    <br/>
    <div id="viewTableControl" class="col-md-12 col-lg-12"></div>


</div>




<script>


    function initViewForm() {
        viewCategory();
    }


    function viewCategory() {

        loaderShow();

        $.get("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/arbitration/coefficients/"+$("#category").val(), {}, function (response) {
            loaderHide();
            $("#viewTableControl").html(response);



        }).done(function() {
            loaderShow();
        })
            .fail(function() {
                loaderHide();
            })
            .always(function() {
                loaderHide();
            });


    }

    function reloadSelect() {
        window.parent.jQuery.fancybox.close();
        viewCategory();
    }


</script>