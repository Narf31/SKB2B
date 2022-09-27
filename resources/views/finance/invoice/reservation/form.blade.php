<div class="col-md-12" style="margin-top: 10px; margin-bottom: 30px">
    <a class="pull-left btn btn-primary" href="/finance/invoice">Назад</a>
    <a class="pull-right btn btn-success" data-submit>Сохранить</a>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">
            <div class="col-md-12" style="margin-bottom: 30px">
                <h1 class="inline-h1">{{ isset($reservation) ? "Счёт № {$reservation->id}" : "Создание резервного счёта" }}</h1>
                @if(isset($reservation))
                    <a class="pull-right btn btn-info doc_export_btn" href="/finance/invoice/reservation/{{$reservation->id}}/export">Печать</a>
                @endif
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Комментарий</label>
                <div class="col-sm-8">
                    {{ Form::textarea('data[comment]', isset($reservation->data['comment']) ? $reservation->data['comment'] : "", ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 30px">
                <h3 class="inline-h1">{{ "Плательщик" }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Инн</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[payer_inn]', isset($reservation->data['payer_inn']) ? $reservation->data['payer_inn'] : "", ['class' => 'form-control party-autocomplete', 'data-side' => 'payer']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Название</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[payer_title]', isset($reservation->data['payer_title']) ? $reservation->data['payer_title'] : "", ['class' => 'form-control', 'data-side' => 'payer']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">КПП</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[payer_kpp]', isset($reservation->data['payer_kpp']) ? $reservation->data['payer_kpp'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">ФИО</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[payer_name]', isset($reservation->data['payer_name']) ? $reservation->data['payer_name'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Адрес</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[payer_address]', isset($reservation->data['payer_address']) ? $reservation->data['payer_address'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-bottom: 30px">
                <h3 class="inline-h1">{{ "Получатель" }}</h3>
            </div>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Моя организация</label>
                    <div class="col-sm-8">
                        @php
                        $organizations = \App\Models\Organizations\Organization::query()
                        ->select('id', 'title_doc as title', 'inn', 'kpp', 'address', 'general_manager')
                        ->where('is_actual', 1)->where('is_delete', 0)->where('org_type_id', 1)->get()->keyBy('id');
                        @endphp
                        {{ Form::select('my_org', $organizations->pluck('title', 'id')->prepend('Нет', 0), 0,['class' => 'form-control select2-ws', 'data-side' => 'recipient']) }}
                        {{ Form::hidden('my_org_data', $organizations) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Инн</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[recipient_inn]', isset($reservation->data['recipient_inn']) ? $reservation->data['recipient_inn'] : "", ['class' => 'form-control party-autocomplete', 'data-side' => 'recipient']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Название</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[recipient_title]', isset($reservation->data['recipient_title']) ? $reservation->data['recipient_title'] : "", ['class' => 'form-control', 'data-side' => 'recipient']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">КПП</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[recipient_kpp]', isset($reservation->data['recipient_kpp']) ? $reservation->data['recipient_kpp'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">ФИО</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[recipient_name]', isset($reservation->data['recipient_name']) ? $reservation->data['recipient_name'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">Адрес</label>
                    <div class="col-sm-8">
                        {{ Form::text('data[recipient_address]', isset($reservation->data['recipient_address']) ? $reservation->data['recipient_address'] : "", ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="block-main">
        <div class="block-sub">

            <div class="col-md-12" style="margin-top: 30px">
                <div class="form-group col-md-4">
                    <b>Номер БСО</b>
                </div>
                <div class="form-group col-md-1"></div>

                <div class="form-group col-md-3">
                    <b>Сумма к оплате</b>
                </div>
                <div class="form-group col-md-4"></div>
            </div>

            <div id="data">
                @if(isset($reservation['data']['bso']) && is_array($reservation['data']['bso']) && count($reservation['data']['bso'])>0)
                    @foreach($reservation['data']['bso'] as $k => $bso)

                        <div class="col-md-12" data-row="{{$k}}">
                            <div class="form-group col-md-4">
                                {{ Form::text("data[bso][{$k}][bso_number]", $bso['bso_number'], ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-1"></div>
                            <div class="form-group col-md-3">
                                {{ Form::text("data[bso][{$k}][bso_sum]", $bso['bso_sum'], ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group col-md-4">
                                <a class="pull-right btn btn-danger" data-delete="{{$k}}">Удалить</a>
                            </div>
                        </div>

                    @endforeach
                @else

                    <div class="col-md-12" data-row="0">
                        <div class="form-group col-md-4">
                            {{ Form::text('data[bso][0][bso_number]', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-1"></div>
                        <div class="form-group col-md-3">
                            {{ Form::text('data[bso][0][bso_sum]', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-4">
                            <a class="pull-right btn btn-danger" data-delete="0">Удалить</a>
                        </div>
                    </div>

                @endif
            </div>

            <div class="col-md-12">
                <div class="form-group col-md-8"></div>
                <div class="form-group col-md-4">
                    <a class="pull-right btn btn-primary" data-add>Добавить строку</a>
                </div>
            </div>
        </div>
    </div>
</div>





@section('js')
<script>
    $(function(){


        add_suggestions($('[name*="[bso_number]"]'));

        $(document).on('click', '[data-delete]', function(){
            var rows = $('#data').find('[data-row]');
            if(rows.length > 1){
                var id = $(this).data('delete');
                $('[data-row="'+id+'"]').remove();
            }
        });

        $(document).on('click', '[data-add]', function(){
            var data_place = $('#data');
            var row = data_place.find('[data-row]').first();
            var row_clone = row.clone();

            $.each(row_clone.find('input'), function(k,v){ $(v).val(''); });
            $(row_clone).attr('data-row', data_place.find('[data-row]').length);
            $(row_clone).find('[name*="][bso_number]"]').attr('name', 'data[bso]['+data_place.find('[data-row]').length+'][bso_number]');
            $(row_clone).find('[name*="][bso_sum]"]').attr('name', 'data[bso]['+data_place.find('[data-row]').length+'][bso_sum]');
            $(row_clone).find('[data-delete]').attr('data-delete', data_place.find('[data-delete]').length);
            data_place.append(row_clone);

            add_suggestions(row_clone.find('[name*="[bso_number]"]'));

        });


        $(document).on('click', '[data-submit]', function(){
            var form = $(this).closest('form');
            var data = form.serialize();
            $.post(form.attr('action'), data, function(res){
                if(form.data('type') === 'edit'){
                    flashMessage('success', 'Сохранено успешно')
                }else{
                    if(res.status === 'ok'){
                        location.href = '/finance/invoice/reservation/'+res.id+'/edit';
                    }
                }
            }).fail(function(res){
                flashValidationErrors(res.responseJSON.errors);
            })
        });


        $(document).on('change', '[name="my_org"]', function(){

            var id = $(this).val();

            if(id > 0){

                var my_org_data = JSON.parse($('[name="my_org_data"]').val())[id];

                $('[name="data[recipient_title]"]').val(my_org_data.title);
                $('[name="data[recipient_inn]"]').val(my_org_data.inn);
                $('[name="data[recipient_kpp]"]').val(my_org_data.kpp);
                $('[name="data[recipient_address]"]').val(my_org_data.address);
                $('[name="data[recipient_name]"]').val(my_org_data.general_manager);

            }else{
                $('[name="data[recipient_title]"]').val('');
                $('[name="data[recipient_inn]"]').val('');
                $('[name="data[recipient_kpp]"]').val('');
                $('[name="data[recipient_address]"]').val('');
                $('[name="data[recipient_name]"]').val('');


            }


        });


        $(".party-autocomplete").suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var side = $(this).data('side');
                var data = suggestion.data;

                $('[name="data['+side+'_title]"]').val(data.name.full);
                $('[name="data['+side+'_inn]"]').val(data.inn);
                $('[name="data['+side+'_kpp]"]').val(data.kpp);
                $('[name="data['+side+'_address]"]').val(data.address.value);
                if(data.management && data.management.name){
                    $('[name="data['+side+'_name]"]').val(data.management.name);
                }

            }
        });

    });

    function add_suggestions(elem){
        console.log(elem);
        elem.suggestions({
            serviceUrl: "/bso/actions/get_bso/",
            type: "PARTY",
            params:{type_bso:0, bso_supplier_id:0, bso_agent_id:0},
            count: 5,
            minChars: 3,
            formatResult: function (e, t, n, i) {
                var s = this;
                var title = n.value;
                var bso_type = n.data.bso_type;
                var bso_sk = n.data.bso_sk;
                var agent_name = n.data.agent_name;

                var view_res = title;
                view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">СК</span>' + bso_sk + "</div>";
                view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Тип</span>' + bso_type + "</div>";
                view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Агент</span>' + agent_name + "</div>";

                return view_res;
            },
            onSelect: function (suggestion) {
                $(this).val(suggestion.data.bso_title);
            }
        });
    }

</script>
@endsection