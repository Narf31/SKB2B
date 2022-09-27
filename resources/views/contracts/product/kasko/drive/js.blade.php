<script>


    $(function () {

        init();

    });


    function init() {

        formatDate();
        initTerms();
        initStartInsureds();
        initCar();

        initDocument();



        setTimeout(function tick() {
            $("#loadPage").hide();
        }, 500);

    }






</script>

