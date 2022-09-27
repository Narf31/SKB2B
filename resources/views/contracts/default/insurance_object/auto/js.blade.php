<script>

    function initCar() {

        $("#carYear").mask('9999');

        @if((int)$object->mark_id > 0 && (int)$object->model_id > 0)
            getMarkObjectInsurer({{$object->mark_id}}, {{$object->model_id}}, '{{$object->model_classification_code}}');
        @elseif((int)$object->mark_id > 0)
            getMarkObjectInsurer({{$object->mark_id}}, 0, 0);
        @else
            getMarkObjectInsurer(0, 0, 0);
        @endif

        viewDK();
        viewDocType();

        var current = parseInt($('#object_ts_category').val());

        if (current == 4) {
            $('.view-dop').show();
            $('.view-dop-passengers').hide();
        } else if (current == 1) {
            $('.view-dop').hide();
            $('.view-dop-passengers').show();
        } else {
            $('.view-dop').hide();
            $('.view-dop-passengers').hide();
        }

    }

    function viewDK() {
        carYear = $("#carYear").val();
        if(carYear.length > 3 && (parseInt('{{date("Y")}}')-parseInt(carYear)) > 3){
            $(".view-dk").show();
            $(".form-dk").addClass('valid_accept');
        }else{
            $(".view-dk").hide();
            $(".form-dk").removeClass('valid_accept');
            $(".form-dk").val('');
        }
    }

    function viewCategory() {

        var current = parseInt($('#object_ts_category').val());

        if (current == 4) {
            $('.view-dop').show();
            $('.view-dop-passengers').hide();
        } else if (current == 1) {
            $('.view-dop').hide();
            $('.view-dop-passengers').show();
        } else {
            $('.view-dop').hide();
            $('.view-dop-passengers').hide();
        }

        getMarkObjectInsurer(0, 0, 0);


    }

    function getMarkObjectInsurer(select_mark_id, select_model_id, select_model_classification_code)
    {

        $.getJSON('/contracts/online/{{$contract->id}}/action/product/auto/'+$('#object_ts_category').val()+'/mark/', {}, function (response) {

            is_select = 0;


            var options = "<option value='0'>Не выбрано</option>";
            response.map(function (item) {

                if(item.id == select_mark_id){
                    is_select = 1;
                }

                options += "<option value='" + item.id + "'>" + item.title + "</option>";
            });

            if(is_select == 0){
                select_mark_id = 0;
            }

            $("#object_ts_mark_id").html(options).select2('val', select_mark_id);
            getModelsObjectInsurer(select_model_id, select_model_classification_code);
        });


    }

    function getModelsObjectInsurer(select_model_id, select_model_classification_code)
    {

        $.getJSON('/contracts/online/{{$contract->id}}/action/product/auto/'+$('#object_ts_category').val()+'/models/'+$('#object_ts_mark_id').val(), {}, function (response) {

            is_select = 0;


            var options = "<option value='0'>Не выбрано</option>";
            response.map(function (item) {

                if(item.id == select_model_id){
                    is_select = 1;
                }

                options += "<option value='" + item.id + "'>" + item.title + "</option>";
            });

            if(is_select == 0){
                select_model_id = 0;
            }

            $("#object_ts_model_id").html(options).select2('val', select_model_id);

        });

    }

    function viewDocType() {
        ts_doc_type = $("#object_ts_doc_type").val();


        if(ts_doc_type == 225346){
            $('#docserie').hide();
            $('#docnumber').removeClass('col-md-4 col-lg-3');
            $('#docnumber').addClass('col-md-8 col-lg-6');
        }else{
            $('#docserie').show();
            $('#docnumber').removeClass('col-md-8 col-lg-6');
            $('#docnumber').addClass('col-md-4 col-lg-3');
        }

    }

    function setVIN() {
        if($('#not_vin').prop('checked')){
            $('#object_ts_vin').val('ОТСУТСТВУЕТ');
            $('#object_ts_vin').attr('readonly',true);
        }else{
            $('#object_ts_vin').attr('readonly',false);
            if($('#object_ts_vin').val() == 'ОТСУТСТВУЕТ'){
                $('#object_ts_vin').val('');
            }
        }
    }

</script>

