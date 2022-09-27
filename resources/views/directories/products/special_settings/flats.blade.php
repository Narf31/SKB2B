@extends('layouts.app')


@section('content')



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">
    {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings"), 'method' => 'post', "autocomplete" =>"off", 'files' => true]) }}

    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <h4>Сервисная компания</h4>
                <br/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Название</label>
                    <div class="col-sm-9">
                        {{ Form::text('json[service_company][title]', ((isset($json) && isset($json->service_company))?$json->service_company->title : ''), ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Техподдержка</label>
                    <div class="col-sm-9">
                        <div class="row form-horizontal">
                            <div class="col-sm-6">
                                {{ Form::text('json[service_company][phone]', ((isset($json) && isset($json->service_company))?$json->service_company->phone : ''), ['class' => 'form-control phone', 'placeholder'=>'Телефо']) }}
                            </div>
                            <div class="col-sm-6">
                                {{ Form::text('json[service_company][email]', ((isset($json) && isset($json->service_company))?$json->service_company->email : ''), ['class' => 'form-control', 'placeholder'=>'Email']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <br/>
                <div class="row">
                    <label class="col-sm-12">Скидки
                        <span class="btn btn-success pull-right" style="width: 30px;height: 25px;font-size: 10px;" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/official_discount/0/")}}')" >
                            <i class="fa fa-plus"></i>
                        </span>
                    </label>
                    <div class="col-sm-12">

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Риски</th>
                                <th>Тип</th>
                                <th>Скидка</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->special_settings_discount as $discount)
                                <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/official_discount/{$discount->id}/")}}')">
                                    <td>{!! $discount->getTitleFlatsDiscont() !!}</td>
                                    <td>{{\App\Models\Directories\Products\ProductsOfficialDiscount::TYPE[$discount->type_id]}}</td>
                                    <td>{{titleFloatFormat($discount->discount)}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>


                <br/>
                <div class="form-group">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary pull-left">
                            Сохранить
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{ Form::close() }}

    </div>


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
        <h2>Дополнительные документы</h2>

        <div class="row form-group">
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if($special_settings->files->count())
                        <table class="table orderStatusTable dataTable no-footer">

                            <tbody>
                            @foreach($special_settings->files as $file)
                                <div class="col-lg-3">
                                    <div class="upload-dot">
                                        <div class="block-image">
                                            @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif']))
                                                <a href="{{ url($file->url) }}" target="_blank">
                                                    <img class="media-object preview-image" src="{{ url($file->preview) }}" onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                                                </a>
                                            @else
                                                <a href="{{ url($file->url) }}" target="_blank">
                                                    <img class="media-object preview-icon" src="/images/extensions/{{$file->ext}}.png">
                                                </a>
                                            @endif
                                            <div class="upload-close">
                                                <div class="" style="float:right;color:red;">
                                                    <a href="javascript:void(0);" onclick="removeProductFile('{{ $file->name }}')">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3>{{ trans('form.empty') }}</h3>
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! Form::open(['url'=>"/directories/products/{$product->id}/edit/special-settings/save_files",'method' => 'post', 'class' => 'dropzone', 'id' => 'addManyDocForm']) !!}
                    <div class="dz-message" data-dz-message>
                        <p>Перетащите сюда файлы</p>
                        <p class="dz-link">или выберите с диска</p>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>




    <br/>



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Риски</h2>
            <a class="btn btn-success pull-right" style="width: 30px;height: 25px;font-size: 10px;" href="{{ url("/directories/products/{$product->id}/edit/special-settings/0/risks") }}">
                <i class="fa fa-plus"></i>
            </a>
        </div>

        <div class="row form-group">
            <div class="col-sm-12">

                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Раздел</th>
                        <th>Объект страхования</th>
                        <th>Страховые риски, страховые случаи</th>
                        <th>Выгодоприобретатель</th>
                        <th>Территория страхования</th>
                        <th>Страховая сумма</th>
                        <th>Страховая премия</th>
                    </tr>
                    </thead>
                    <tbody class="sortable_table_columns" data-type="{{$product->id}}">
                    @foreach($product->flats_risks as $flats_risks)
                        <tr id="infodata-{{$flats_risks->id}}" style="cursor: pointer;">
                            <td><a href="{{ url("/directories/products/{$product->id}/edit/special-settings/{$flats_risks->id}/risks") }}">{{$flats_risks->title}}</a></td>
                            <td>{!! $flats_risks->insurance_object !!}</td>
                            <td>{!! $flats_risks->risks_events !!}</td>
                            <td>{{$flats_risks->beneficiary}}</td>
                            <td>{{$flats_risks->insurance_territory}}</td>
                            <td>{{titleFloatFormat($flats_risks->insurance_amount)}} {{$flats_risks->insurance_amount_comment}}</td>
                            <td>{{titleFloatFormat($flats_risks->payment_total)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>










@endsection



@section('js')

    <script>


        $(function () {

            $('.sortable_table_columns').sortable({
                axis: 'y',
                update: function (event, ui) {
                    var data = $(this).sortable('serialize');

                    // POST to server using $.post or $.ajax
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: "{{ url("/directories/products/{$product->id}/edit/special-settings/sort") }}?type="+$(this).data("type")
                    });
                }
            });

        });


        function removeProductFile(fileName) {
            if (!customConfirm()) {
                return false;
            }
            var filesUrl = '{{url("/directories/products/{$product->id}/edit/special-settings/delete-file")}}';
            var fileUrl = filesUrl + '/' + fileName;
            $.post(fileUrl, {
                _method: 'DELETE'
            }, function () {
                reload();

            });
        }


    </script>


@endsection