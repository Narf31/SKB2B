<script>


    $(function () {

        init();

    });


    function init() {


        initTerms();
        initStartInsureds();


        //initDocument();

    }



    function getCheckDisabled()
    {

        $(".dop_programs").prop("disabled", false);

        loaderShow();

        $.post('/contracts/online/{{$contract->id}}/action/view-control', $('#product_form').serialize(), function (response) {


            for(var k in response) {
                $("#"+response[k]).select2("val", "0");
                $("#"+response[k]).prop("disabled", true);
            }

        }).always(function () {
            loaderHide();
        });

        return true;
    }



</script>

