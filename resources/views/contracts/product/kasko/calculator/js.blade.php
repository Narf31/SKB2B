<script>


    $(function () {

        init();

    });


    function init() {

        formatDate();
        initTerms();
        initCar();
        getModelsObjectInsurerCalc();

        //initDocument();



        setTimeout(function tick() {
            $("#loadPage").hide();
        }, 500);

    }


    function getModelsObjectInsurerCalc() {

        if($('#is_multidriver').val() != 1){
            $('.is_multidriver_0').show();
            $('.is_multidriver_0').find('input').addClass('valid_accept');
        }else{
            $('.is_multidriver_0').hide();
            $('.is_multidriver_0').find('input').removeClass('valid_accept');
        }

    }



</script>

