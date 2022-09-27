<div class="form-group">
    <label class="col-sm-4 control-label">Филиал</label>
    <div class="col-sm-8">
        {{ Form::select('bso_supplier_id', \App\Models\Directories\BsoSuppliers::getFilials()->get()->pluck('title', 'id'), auth()->user()->organization_id, ['class' => 'form-control select2-all', 'id'=>'bso_supplier_id']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-12 control-label"><input type="checkbox" class="cb_left_column_style" />Указывать диапазон БСО</label>
</div>


<div class="form-group tr_type_selector">
    <label class="col-sm-4 control-label">Тип</label>
    <div class="col-sm-8">
        <select id="bso_type_id" class="form-control"></select>
    </div>
</div>

<div class="form-group tr_type_selector">
    <label class="col-sm-4 control-label">Статус</label>
    <div class="col-sm-8">
        {{ Form::select('bso_state_id', \App\Models\BSO\BsoState::all()->pluck('title','id'), 0, ['id'=> 'bso_state_id', 'class' => 'form-control']) }}
    </div>
</div>



@section('js')

    <script>


        $(function () {




            $('#bso_supplier_id').on('change', function () {
                $("#bso_type_id").html(myGetAjax('/bso/transfer/get_bso_types/?sk_user_id=' + $(this).val()));
            });

            $("#bso_type_id").on('change', function () {
                getBsosList($(this).val());
            });

            $("#bso_state_id").on('change', function () {
                getBsosList($("#bso_type_id").val());
            });

            $(document).on(
                "click",
                ".cb_left_column_style",
                function () {

                    if ($(this).prop("checked")) {
                        $('.tr_type_selector').hide();
                        $('#bsos').hide();
                        $('#rit_bsos').show();
                        $('.save_transmit_bso').show();
                        document.cookie = "bso_transfer_left_column_style=1";

                        if ($('.bso_table[bso_supplier_id=' + $('#bso_supplier_id').val() + ']').length == 0) {
                            res = myGetAjax('/bso/transfer/bso_selector/?bso_supplier_id=' + $('#bso_supplier_id').val());
                            $('#rit_bsos').html(res);

                            $(".type_selector").on('change', function () {
                                selectBsoType(this);
                            });

                            $(".bso_number").on('change', function () {
                                selectBsoNumber(this);
                            });

                            $(".bso_qty").on('change', function () {
                                selectBsoQty(this);
                            });


                            $(document).on(
                                "click",
                                ".remove_string_button",
                                function () {

                                    $(this).parent().parent().remove();
                                }
                            );


                            $(document).on(
                                "click",
                                ".save_transmit_bso",
                                function () {
                                    var errors_qty = 0;
                                    $.each($('.table_row[completed=0]'), function () {

                                        $(this).find('.error_div').html('');
                                        $(this).find('td:nth-child(2)').children('.error_div').html('');

                                        obj = new Object();
                                        obj.type_id = $(this).find('.type_selector').val();
                                        obj.serie_id = $(this).find('.series_selector').val();
                                        obj.bso_qty = $(this).find('.bso_qty').val();
                                        obj.number = $(this).find('.bso_number').val();
                                        obj.tp_id = '{{$bso_cart->tp_id}}';
                                        obj.bso_cart_id = '{{$bso_cart->id}}';
                                        res = myGetAjax('/bso/transfer/selector_bso_transfer/?obj=' + JSON.stringify(obj));

                                        //res = JSON.parse(r);
                                        if (res.error_state == 1) {
                                            $(this).find('td:nth-child(' + res.error_attr + ')').children('.error_div').html(res.error_title);
                                            $(this).children().css('background-color', '#FFEEEE');
                                            errors_qty++;
                                        }
                                        else {
                                            $(this).attr('completed', 1);
                                            $(this).find('select').prop('disabled', true);
                                            $(this).find('input').prop('disabled', true);
                                            if (res.error_state != 2) $(this).children().css('background-color', '#EEFFEE');
                                        }

                                    });

                                    if (errors_qty > 0) {
                                        alert('Перемещены не все БСО!');
                                    }

                                    openViewCar();

                                }
                            );

                        }
                    }
                    else {
                        $('.tr_type_selector').show();
                        $('#bsos').show();
                        $('#rit_bsos').hide();
                        $('.save_transmit_bso').hide();
                        document.cookie = "bso_transfer_left_column_style=0";
                        $('#rit_bsos').html('');
                    }

                }
            );



            $(document).on(
                "click",
                "#check_all",
                function () {
                    if ($(this).prop("checked")) {
                        $(".cb_bso").prop("checked", true);
                    }
                    else {
                        $(".cb_bso").prop("checked", false);
                    }
                }
            );


            $(document).on(
                "click",
                ".cb_right_column_style",
                function () {

                    if ($(this).prop("checked")) {
                        $('#group_by_items').hide();
                        $('#group_by_types').show();

                    }
                    else {
                        $('#group_by_items').show();
                        $('#group_by_types').hide();

                    }

                }
            );

            $(document).on(
                "click",
                "#move_to_cart",
                function () {

                    var bsos = '';
                    $('.cb_bso:checked').each(function () {
                        bsos += $(this).attr('bso_id') + ',';
                    });

                    //Резервируем бсо
                    var res = myGetAjax('/bso/transfer/move_to_cart/?bso_cart_id={{$bso_cart->id}}' + '&bsos=' + bsos);

                    //Обновляем левую корзину
                    getBsosList($("#bso_type_id").val());

                    //Обновляем правую корзину
                    openViewCar();
                }
            );


            $(document).on(
                "click",
                ".remove_button",
                function () {
                    var bso_id = $(this).attr('bso_id');
                    removeBsosCar(bso_id, 0);
                }
            );

            $(document).on(
                "click",
                ".remove_type_button",
                function () {
                    var bso_type_id = $(this).attr('type_id');
                    removeBsosCar(0, bso_type_id);
                }
            );



            $("#b_transfer_bso").on('click', function () {

                if (!confirm('Подвердите передачу БСО')) return false;

                var res = myGetAjax('/bso/transfer/transfer_bso/?bso_cart_id={{$bso_cart->id}}');

                if (res == 'empty cart') {
                    alert('В корзине отсутствуют БСО.');
                    return false;
                }

                reload();

            });


            $('#bso_supplier_id').change();
            $('.cb_left_column_style').click();



            openViewCar();

        });


        function getBsosList(bso_type_id)
        {
            var bso_supplier_id = $('#bso_supplier_id').val();
            var user_id_from = $('#user_id_from').val();
            var bso_cart_type = $('#bso_cart_type').val();
            var bso_state_id = $('#bso_state_id').val();
            var tp_id = '{{$bso_cart->tp_id}}';

            $('#bsos').html(myGetAjax('/bso/transfer/get_bsos/?bso_supplier_id=' + bso_supplier_id + '&bso_type_id=' + bso_type_id + '&user_id_from=' + user_id_from + '&bso_cart_type=' + bso_cart_type + '&bso_state_id=' + bso_state_id + '&tp_id=' + tp_id));

        }


        function openViewCar()
        {
            $('#bso_cart').html(myGetAjax('/bso/transfer/bso_cart_content/?bso_cart_id={{$bso_cart->id}}'));

            if ($('.cb_right_column_style').prop("checked")) {
                $('#group_by_items').hide();
                $('#group_by_types').show();

            }
            else {
                $('#group_by_items').show();
                $('#group_by_types').hide();

            }
        }


        function removeBsosCar(bso_id, bso_type_id)
        {
            myGetAjax('/bso/transfer/remove_from_bso_cart/?bso_id=' + bso_id + '&bso_type_id=' + bso_type_id + '&bso_cart_id={{$bso_cart->id}}');

            if (!$('.cb_left_column_style').prop("checked")) {
                getBsosList($("#bso_type_id").val());
            }

            openViewCar();
        }


        function removeCar()
        {
            res = myGetAjax('/bso/transfer/remove_cart/?bso_cart_id={{$bso_cart->id}}');
            if(res == 200){
                openPage('/bso_acts/acts_reserve/');
            }
        }


    </script>



@stop