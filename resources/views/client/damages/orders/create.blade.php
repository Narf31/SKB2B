@extends('client.layouts.app')

@section('head')

@append


@if(sizeof($contracts))

@section('content')

    <div class="row row__custom justify-content-between">


        <div class="reviews__item form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form method="POST" id="data-form">

                <div class="row row__custom">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field" style="margin-top: 10px;margin-left: 5px;font-size: 18px;font-weight: bold;">
                            Заявка на страховой случай
                        </div>
                    </div>
                </div>


                <div class="alert alert-danger text-center" id="errors-text" style="display: none;">
                </div>

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                <div class="row row__custom">

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <div class="select__wrap">
                                {{Form::select("city_id", $city->pluck('title', 'id'), old('city_id'), ['id' => 'city_id', 'onchange'=>"get_executors();"]) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <div class="select__wrap">
                                {{Form::select("contract_id", $contracts->pluck('bso_title', 'id'), old('contract_id'), ['class' => '']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="phone" name="phone"  value="{{old('phone')}}" >
                            <div class="form__label">Телефон <span class="required">*</span></div>
                        </div>
                    </div>

                </div>

                <div class="row row__custom">


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col__custom form__item">
                        <div class="form__field">
                            <div class="select__wrap">
                                {{Form::select("position_type_id", \App\Models\Orders\Damages::POSITION_TYPE, old('position_type_id'), ['onchange'=>"getViewFormPositionType();", 'id'=>'position_type_id']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col__custom form__item" id="form-address">
                        <div class="form__field">
                            {{ Form::text('address', '', ['class' => '', 'id'=>'object_address']) }}
                            <input name="latitude" id="object_address_latitude" value="" type="hidden"/>
                            <input name="longitude" id="object_address_longitude" value="" type="hidden"/>
                            <div class="form__label">Адрес <span class="required">*</span></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col__custom form__item" id="form-point-sale">
                        <div class="form__field">
                            <div class="select__wrap">
                            {{ Form::select('point_sale_id', collect([]), 0, ['class' => '', 'id'=>'point_sale_id']) }}
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row row__custom">


                    <div class="col-xs-12 col-sm-4 col-md-2 col-lg-1 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="time" name="time"  value="12:00" >
                            <div class="form__label">Время <span class="required">*</span></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-3 col-lg-3 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="format-date date" name="date"  value="{{date('d.m.Y', strtotime("+1 day"))}}" >
                            <div class="form__label">Дата <span class="required">*</span></div>
                        </div>
                    </div>



                </div>

                <div class="row row__custom">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field">
                            {{ Form::textarea('comments', '', ['' => '']) }}
                            <div class="form__label">Причина</div>
                        </div>
                    </div>

                </div>

                <br/><br/>
                <div class="row row__custom">
                    <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <span class="btn__round d-flex align-items-center justify-content-center" onclick="createOrder()">
                            Создать заявку
                        </span>

                    </div>
                </div>
            </form>
        </div>




    </div>



@endsection



@section('js')

    <script>
        $(function () {

            activeInputForms();
            initActivForms();

        });


        function initActivForms() {
            get_executors();
            getViewFormPositionType();

            $('.phone').mask('+7 (999) 999-99-99');
            $('.time').mask('99:99');


            $('#object_address').suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "ADDRESS",
                count: 5,
                onSelect: function (suggestion) {

                    key = $(this).data('key');
                    $('#object_address').val($(this).val());
                    $('#object_address_latitude').val(suggestion.data.geo_lat);
                    $('#object_address_longitude').val(suggestion.data.geo_lon);

                }
            });

        }


        function get_executors() {
            city_id = $("#city_id").val();

            $.post(
                '{{urlClient("/damages/actions/get-point-sale")}}',
                {city_id:city_id, type:1},
                function (response) {
                    select_val = 0;
                    var options = '';
                    response.map(function (item) {
                        if(select_val == 0){
                            select_val = item.id;
                        }
                        options += "<option value='" + item.id + "'>" + item.title + " - " + item.address + "</option>";
                    });

                    $('#point_sale_id').html(options).trigger('refresh');//.select2('val', select_val);

                });
        }

        function getViewFormPositionType() {
            position_type_id = $("#position_type_id").val();
            if(parseInt(position_type_id) == 1){
                $("#form-address").hide();
                $("#form-point-sale").show();
            }else{
                $("#form-address").show();
                $("#form-point-sale").hide();
            }
        }


        function createOrder() {

            loaderShow();

            $.post("{{urlClient("/damages/create")}}", $('#data-form').serialize(), function (response) {


                if (Boolean(response.state) === true) {

                    window.location = '/damages/order/'+response.damage_id;

                }else {
                    setError(response.msg);

                }

            }).always(function () {
                loaderHide();
            });

            return true;
        }


        function setError(msg) {
            $("#errors-text").html(msg);
            $("#errors-text").show();
        }

    </script>

@endsection

@else
@section('content')

    <div class="row text col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12">

        <br/><br/><br/><br/>
        <p style="font-size: 18px;">
            У вас нет действующих договоров
        </p>
    </div>

@endsection
@endif