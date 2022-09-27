
<div class="form-group">
    <label class="col-sm-4 control-label">Продукт</label>
    <div class="col-sm-8">
        {{ Form::select('product_id', \App\Models\Directories\Products::where('is_actual', '=', '1')->get()->pluck('title', 'id'), old('product_id'), ['class' => 'form-control', 'id' => 'product_id', 'required', "onchange" => "$(\"#product_title\").text($(\"#product_id option:selected\").text())"]) }}
    </div>
</div>

