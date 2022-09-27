
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">


    <div class="block-main">
        <div class="block-sub">

            <div class="form-group">
                <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
                <div class="col-sm-2">
                    {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Осмотр</label>
                <div class="col-sm-8">
                    <label class="control-label" style="padding: 0 0 0 10px">Нет</label>
                    {{ Form::radio('for_inspections', 0, old('for_inspections')) }}
                    <label class="control-label" style="padding: 0 0 0 10px">Выезды</label>
                    {{ Form::radio('for_inspections', 1, old('for_inspections')) }}
                    <label class="control-label" style="padding: 0 0 0 10px">Точка осмотра</label>
                    {{ Form::radio('for_inspections', 2, old('for_inspections')) }}
                </div>
            </div>
            @php($style = '')
            @if(isset($product) && $product->for_inspections == 0)
                @php($style = 'display:none')
            @endif
            <div class="form-group" id="inspection_temple_act" style="{{$style}}">
                <label class="col-sm-4 control-label">Акт осмотра</label>
                <div class="col-sm-8">
                    {{ Form::select('inspection_temple_act', collect(\App\Models\Directories\Products::INSPECTION_TEMPLE_ACT), old('inspection_temple_act'), ['class' => 'form-control select2-ws']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
                <div class="col-sm-8">
                    {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Тип фин. политики</label>
                <div class="col-sm-8">
                    {{ Form::select('financial_policy_type_id', collect(\App\Models\Directories\Products::FIN_TYPE), old('financial_policy_type_id'), ['class' => 'form-control select2-ws', 'required']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Категория</label>
                <div class="col-sm-8">
                    {{ Form::select('category_id', \App\Models\Directories\ProductsCategory::orderBy('sort', 'asc')->get()->pluck('title', 'id'), old('category_id'), ['class' => 'form-control select2-ws', 'required']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Код API</label>
                <div class="col-sm-8">
                    {{ Form::text('code_api', old('code_api'), ['class' => 'form-control']) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Доступ к КВ Оф.</label>
                <div class="col-sm-8">
                    {{ Form::checkbox('kv_official_available', 1, old('kv_official_available')) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-4 control-label">Доступ к КВ Неоф.</label>
                <div class="col-sm-8">
                    {{ Form::checkbox('kv_informal_available', 1, old('kv_informal_available')) }}
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-4 control-label">Доступ к КВ Банк</label>
                <div class="col-sm-8">
                    {{ Form::checkbox('kv_bank_available', 1, old('kv_bank_available')) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Отчеты ДВОУ</label>
                <div class="col-sm-2">
                    {{ Form::checkbox('is_dvou', 1, old('is_dvou')) }}
                </div>
            </div>
        </div>
    </div>
</div>





<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-group">
                <label class="col-sm-4 control-label">Доступно оформление онлайн</label>
                <div class="col-sm-2">
                    {{ Form::checkbox('is_online', 1, old('is_online')) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Общий интерфейс расчета</label>
                <div class="col-sm-2">
                    {{ Form::checkbox('is_common_calculation', 1, old('is_common_calculation')) }}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-12">
                    Ключ классов
                    @if(isset($product) && $product->id > 0)<a class="pull-right is_product" href="{{url("/directories/products/{$product->id}/edit/special-settings/")}}" >Спец настройки</a>@endif
                </label>
                <div class="col-sm-12" >
                    {{ Form::select('slug', collect(\App\Models\Directories\Products::SLUG), old('slug'), ['class' => 'form-control select2-ws', 'id' => 'slug', "onchange"=>"selectTypeSlug()"]) }}
                </div>
            </div>



            <div class="is_product">

                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Маска договора <br/>
                        @if(isset($product) && $product->id > 0)
                            @if($product->template)
                                <a href="{{ $product->template->getUrlAttribute() }}" target="_blank" style="float: left">{{ $product->template->original_name }}</a>
                            @endif
                        @endif
                    </label>
                    <div class="col-sm-8">
                        {{ Form::file('file', ['class' => 'file-input']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Договор <br/>
                        @if(isset($product) && $product->id > 0)
                            @if($product->template_contract)
                                <a href="{{ $product->template_contract->getUrlAttribute() }}" target="_blank" style="float: left">{{ $product->template_contract->original_name }}</a>
                            @endif
                        @endif
                    </label>
                    <div class="col-sm-8">
                        {{ Form::file('file_contract', ['class' => 'file-input']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Заявление <br/>
                        @if(isset($product) && $product->id > 0)
                            @if($product->template_statement)
                                <a href="{{ $product->template_statement->getUrlAttribute() }}" target="_blank" style="float: left">{{ $product->template_statement->original_name }}</a>
                            @endif
                        @endif
                    </label>
                    <div class="col-sm-8">
                        {{ Form::file('file_statement', ['class' => 'file-input']) }}
                    </div>
                </div>


                @if(isset($product) && $product->id > 0)
                <div class="form-group">
                    <label class="col-sm-4 control-label">Печать</label>
                    <div class="col-sm-4">
                        {{ Form::select('template_print', collect([''=>'Нет', "tit_print" => 'ТИТ печать и подпись']), $product->template_print, ['class' => 'form-control select2-ws', 'id' => 'slug']) }}
                    </div>
                    <div class="col-sm-2">
                        {{ Form::text('template_print_x', $product->template_print_x, ['class' =>'form-control', 'placeholder' => 'X']) }}
                    </div>
                    <div class="col-sm-2">
                        {{ Form::text('template_print_y', $product->template_print_y, ['class' =>'form-control', 'placeholder' => 'Y']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Подпись</label>
                    <div class="col-sm-4">
                        {{ Form::select('template_signature', collect([''=>'Нет']), $product->template_signature, ['class' => 'form-control select2-ws', 'id' => 'slug']) }}
                    </div>
                    <div class="col-sm-2">
                        {{ Form::text('template_signature_x', $product->template_signature_x, ['class' =>'form-control', 'placeholder' => 'X']) }}
                    </div>
                    <div class="col-sm-2">
                        {{ Form::text('template_signature_y', $product->template_signature_y, ['class' =>'form-control', 'placeholder' => 'Y']) }}
                    </div>
                </div>
                @endif

            </div>



            <div class="is_program">
                @if(isset($product) && $product->id > 0)

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Программа</th>
                            <th>Спец настройки</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->programs as $program)
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/programs/{$program->id}")}}')">
                                        {{$program->title}} - {{($program->is_actual == 1?"Актуально":"Нет")}}
                                    </a>
                                </td>
                                <td><a href="{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/{$product->slug}")}}">Спец настройки</a></td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>

                <span class="btn btn-info pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/programs/0")}}')">Добавить программу</span>

                <br/><br/>

                @endif
            </div>



        </div>
    </div>




</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="form-group">
        <label class="col-sm-12">
            Описание продукта
            @if(isset($product) && $product->id > 0)<a class="pull-right" href="{{url("/directories/products/{$product->id}/edit/info/")}}" >Инструкция</a>@endif
        </label>
        <div class="col-sm-12">
            {{ Form::textarea('description', old('description'),['rows' => 2,  'class' =>'form-control', 'style' => 'width:100%;height:195px', 'id' => 'text_description']) }}
        </div>
    </div>
</div>


<script>

    function viewProgram() {

        slug = $("#slug").val();
        if(slug == 'kasko' || slug == 'arbitration' || slug == ''){
            $('.is_product').hide();
            if(slug == 'kasko' || slug == 'arbitration')
            {
                $('.is_program').show();
            }else{
                $('.is_program').hide();
            }

        }else{
            $('.is_product').show();
            $('.is_program').hide();
        }

    }

</script>