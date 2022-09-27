<div class="block-inner">

    <div id="table_vue" class="scrollbar-inner">
        {{--Обертка для клона шапки--}}
        <div v-bind:style="{ top: head_offset + 'px', width: max_w + 'px'}" class='cloned_head_wrapper'>
            <table v-bind:style="{width: max_w + 'px'}" class='table  table-bordered'></table>
        </div>

        {{--Обычная таблица как везде, вставляем bind на высоту--}}

        <div id="table" style="overflow-y: visible; }}"
                {{--v-bind:style="{ 'max-height' : table_height + 'px'}"--}}></div>
    </div>
</div>

<style>
    .scrollbar-inner > .scroll-element.scroll-x {
        bottom: 2px;
        height: 10px;
        left: 280px;
        width: 60%;
        position: fixed;
    }
</style>
