
<div class="page-heading" >
    <h2 class="inline-h1">Дополнительное оборудование
    </h2>
</div>

<div id="clone-equipment" class="hidden">
    <div class="row form-horizontal equipment_[[:KEY:]]">
        <div class="col-md-5 col-lg-6" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Название <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[equipment][[[:KEY:]]][title]", '', ['class' => 'form-control [[:VALID:]] clear_offers']) }}
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Страховая сумма <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[equipment][[[:KEY:]]][payment_total]", '', ['class' => 'form-control sum [[:VALID:]] clear_offers']) }}
                </div>
            </div>
        </div>



        <div class="col-md-1 col-lg-1 delete-block" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        &nbsp;
                    </label>
                    <span class="btn btn-info" onclick="deleteEquipment('[[:KEY:]]')">
                         <i class="fa fa-close"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="clear"></div>


        <br/>
        <div class="divider"></div>
        <br/>

    </div>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main-equipments">


                @foreach($equipments as $key => $equipment)
                    @php
                        $key++
                    @endphp
                    <div class="row form-horizontal equipment_{{$key}}">

                        <div class="col-md-5 col-lg-6" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Название <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[equipment][$key][title]", $equipment->title, ['class' => 'form-control [[:VALID:]] clear_offers']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Страховая сумма <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[equipment][$key][payment_total]", titleFloatFormat($equipment->payment_total), ['class' => 'form-control sum [[:VALID:]] clear_offers']) }}
                                </div>
                            </div>
                        </div>



                        <div class="col-md-1 col-lg-1 delete-block" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        &nbsp;
                                    </label>
                                    <span class="btn btn-info" onclick="deleteEquipment('{{$key}}')">
                         <i class="fa fa-close"></i>
                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <br/>
                        <div class="divider"></div>
                        <br/>

                    </div>

                @endforeach


            </div>

            <div class="clear"></div>
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="javascript:void(0);" onclick="addEquipment()">
                    <i class="fa fa-plus"></i>
                    Добавить оборудование
                </a>
            </div>



        </div>
    </div>
</div>


<script>

    var INDEXEquipment = '{{sizeof($equipments)}}';



    function addEquipment()
    {

        String.prototype.replaceAll = function (search, replace) {
            return this.split(search).join(replace);
        }

        INDEXEquipment = parseInt(INDEXEquipment)+1;
        formEquipment = $('#clone-equipment').html().replaceAll('[[:KEY:]]', INDEXEquipment);
        formEquipment = formEquipment.replaceAll('[[:VALID:]]', 'valid_accept');
        formEquipment = formEquipment.replaceAll('[[:SELECT2:]]', 'select2-ws');

        $('#main-equipments').append(formEquipment);
        $('.sum')
            .change(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .blur(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .keyup(function () {
                $(this).val(CommaFormatted($(this).val()));
            });
        $('.clear_offers').change(function() {
            $('#offers').html('');
        });
        $('#offers').html('');
        return INDEXEquipment;
    }

    function deleteEquipment(key)
    {

        $('.equipment_'+key).remove();
        INDEXEquipment = INDEXEquipment-1;
        $('#offers').html('');
    }




</script>