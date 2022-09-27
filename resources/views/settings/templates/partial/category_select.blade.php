@php
$cat_tree = \App\Models\Settings\TemplateCategory::get_all_tree();
$cat_tree = $cat_tree ? $cat_tree : ['result'=>[]];
$selected = isset($selected) ? $selected : 0;
$all = isset($all) ? $all : false;
if($all){
    array_unshift($cat_tree['result'], ['id' => 0, 'parent_id' => 0, 'code' => 'all', 'title' => 'Все', 'has_choise' => 0, 'has_supplier' => 0, '_level' => 1]);
}
@endphp
<select class="form-control select2-ws" onchange="loadItems()" name="category_id">
    @if(is_array($cat_tree['result']) && count($cat_tree['result'])>0)
        @foreach($cat_tree['result'] as $category)
            @php($disabled = $category['id'] != 0 ? isset($cat_tree['_links'][$category['id']]) : false )
            <option {{ $disabled ? "disabled" : "" }}
                value="{{$category['id']}}"
                data-has_supplier="{{$category['has_supplier']}}"
                data-has_choise="{{$category['has_choise']}}"
                {{ $selected == $category['id'] ? "selected" : "" }}>
                
                {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['_level']-1) !!}
                {{ $category['title'] }}
            </option>
        @endforeach
    @endif
</select>