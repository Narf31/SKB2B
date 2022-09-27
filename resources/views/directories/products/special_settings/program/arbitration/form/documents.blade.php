{{ Form::model($product, ['url' => url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/documents/"), 'method' => 'post', 'id' => 'document_form', 'class' => 'form-horizontal col-xs-12 col-sm-12 col-md-6 col-lg-6', 'files' => true]) }}
    @php
       $info= \App\Models\Directories\Products\ProductsSpecialSsettingsFiles::where('special_settings_id',$spec->id)->get();
       $contract = $info->firstWhere('type_name',"contract");
       $policy = $info->firstWhere('type_name',"policy");
       $others = $info->where('type_name',"others");
    @endphp

    <div class="page-heading product_form">
        <h2 class="inline-h1">Документы</h2>
    </div><br/>
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="form-group">
            <label class="col-sm-4 control-label">
                Договор <br/>
                @if(isset($contract) && $file = $spec->files->where('id',$contract->file_id)->first())
                    <a href="{{ url($file->url) }}" target="_blank" style="float: left"><p>{{$file->original_name}}</p></a>
                @endif
            </label>
            <div class="col-sm-8">
                {{ Form::file("contract", ['class' => 'file-input','id' => 'contract-file']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">
                Полис <br/>
                @if(isset($policy) && $file = $spec->files->where('id',$policy->file_id)->first())
                    <a href="{{ url($file->url) }}" target="_blank" style="float: left"><p>{{$file->original_name}}</p></a>
                @endif
            </label>
            <div class="col-sm-8">
                {{ Form::file("policy", ['class' => 'file-input','id' => 'policy-file']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Печать</label>
            <div class="col-sm-4">
                {{ Form::select("template_print", collect([''=>'Нет']), isset($contract) ? $contract->template_print : '', ['class' => 'form-control select2-ws', 'id' => 'slug']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text("template_print_x", isset($contract) ? $contract->template_print_x : '', ['class' =>'form-control', 'placeholder' => 'X']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text("template_print_y", isset($contract) ? $contract->template_print_y : '', ['class' =>'form-control', 'placeholder' => 'Y']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Подпись</label>
            <div class="col-sm-4">
                {{ Form::select("template_signature", collect([''=>'Нет']), isset($contract) ? $contract->template_signature : '', ['class' => 'form-control select2-ws', 'id' => 'slug']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text("template_signature_x", isset($contract) ? $contract->template_signature_x : '', ['class' =>'form-control', 'placeholder' => 'X']) }}
            </div>
            <div class="col-sm-2">
                {{ Form::text("template_signature_y", isset($contract) ? $contract->template_signature_y : '', ['class' =>'form-control', 'placeholder' => 'Y']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">Страница</label>
            <div class="col-sm-4">
                {{ Form::text("template_print_page", isset($contract) ? $contract->template_print_page : '', ['class' =>'form-control']) }}
            </div>
        </div>
    </div>

<div class="clear"></div>
<button type="submit" class="btn btn-success pull-left">Сохранить</button>

{{Form::close()}}
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    <div class="page-heading product_form">
        <h2 class="inline-h1">Дополнительные документы</h2>
    </div><br/>
    <div class="row form-group">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @if(sizeof($others))
                    <table class="table orderStatusTable dataTable no-footer">

                        <tbody>
                        @foreach($spec->files->whereIn('id',$others->pluck('file_id')) as $file)
                            <div class="col-lg-3">
                                <div class="upload-dot">
                                    <div class="block-image">
                                        @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif']))
                                            <a href="{{ url($file->url) }}" target="_blank">
                                                <img class="media-object preview-image" src="{{ url($file->preview) }}" onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                                            </a>
                                        @else
                                            <a href="{{ url($file->url) }}" target="_blank">
                                                <img class="media-object preview-icon" src="/images/extensions/{{$file->ext}}.png"><p>{{ $file->original_name }}</p>
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
                {!! Form::open(['url'=>"/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/save_files",'method' => 'post', 'class' => 'dropzone', 'id' => 'addManyDocForm']) !!}
                <div class="dz-message" data-dz-message>
                    <p>Перетащите сюда файлы</p>
                    <p class="dz-link">или выберите с диска</p>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>



<script>

    function initDocuments() {

        $("#addManyDocForm").dropzone({
            paramName: 'file',
            maxFilesize: 1000,
            acceptedFiles: ".png, .jpg, .jpeg, .txt, .csv, .mp4, .mkv, .avi ,.mov,.avi,.mpeg4,.flv,.3gpp,image/*, .xls, .xlsx, .pdf, .doc, .docx",

        });

    }

    function removeProductFile(fileName) {
        if (!customConfirm()) {
            return false;
        }
        var filesUrl = '{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/delete-file")}}';
        var fileUrl = filesUrl + '/' + fileName;
        $.post(fileUrl, {
            _method: 'DELETE'
        }, function () {
            reload();

        });
    }

    initDocuments();
</script>


