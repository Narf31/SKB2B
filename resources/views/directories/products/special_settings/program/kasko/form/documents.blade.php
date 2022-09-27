{{ Form::model($product, ['url' => url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/documents/"), 'method' => 'post', 'id' => 'document_form', 'class' => 'form-horizontal col-xs-12 col-sm-12 col-md-6 col-lg-6', 'files' => true]) }}
    @php
       $info= \App\Models\Directories\Products\ProductsSpecialSsettingsFiles::where('special_settings_id',$spec->id)->get();
       $contract = $info->firstWhere('type_name',"contract");
       $agreement = $info->firstWhere('type_name',"agreement");
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
                Доп. соглашение <br/>
                @if(isset($agreement) && $file = $spec->files->where('id',$agreement->file_id)->first())
                    <a href="{{ url($file->url) }}" target="_blank" style="float: left"><p>{{$file->original_name}}</p></a>
                @endif
            </label>
            <div class="col-sm-8">
                {{ Form::file("agreement", ['class' => 'file-input','id' => 'agreement-file']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Печать</label>
            <div class="col-sm-4">
                {{ Form::select("template_print", collect([''=>'Нет', "tit_print" => 'ТИТ печать и подпись']), isset($contract) ? $contract->template_print : '', ['class' => 'form-control select2-ws', 'id' => 'slug']) }}
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
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
</div>