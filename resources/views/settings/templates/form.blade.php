@php
$export_item_id = !empty($template) ? $template->export_item_id : 0;
$selected_category = !empty($template) ? $template->category_id : 0;
@endphp
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/templates.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Категория</label>
    <div class="col-sm-8">
        @include('settings.templates.partial.category_select', ['selected' => $selected_category])
    </div>
</div>
<div class="form-group" id="suppliers" style="display: none;">
    <label class="col-sm-4 control-label">Поставщик</label>
    <div class="col-sm-8">
        {{ Form::select('supplier_id', \App\Models\Directories\BsoSuppliers::all()->pluck('title', 'id')->prepend('Универсальный', 0), (!empty($template) ? $template->supplier_id : 0), ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/templates.new_file') }}</label>
    <div class="col-sm-8">
        {{ Form::file('file', ['class' => 'file-input']) }}
    </div>
</div>

@if(!empty($template))
    @if($template->file)
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('settings/templates.file') }}</label>
            <div class="col-sm-8">
                <a href="{{ $template->file->getUrlAttribute() }}" target="_blank" style="float: left">{{ $template->file->original_name }}</a>
            </div>
        </div>
    @endif
@endif


@section('js')
    <script>
        $(function(){

            toggle_suppliers();


            $(document).on('change', '[name="category_id"]', function(){
                toggle_suppliers()


            });


            function toggle_suppliers(){

                var data = $('[name="category_id"]').children(':selected').data();

                if(parseInt(data.has_supplier) === 1){
                    $('#suppliers').show();
                    $(window.parent.document.getElementsByClassName('fancybox-inner')).css({'min-height': '412px'});
                }else{
                    $('#suppliers').hide();
                    $(window.parent.document.getElementsByClassName('fancybox-inner')).css({'min-height': '365px'});
                }
            }


        });
    </script>
@endsection