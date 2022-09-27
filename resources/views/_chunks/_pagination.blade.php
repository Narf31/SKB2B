{{--

    $b_blass string - классы для селекта кол-ва материалов на странице
    $callback func name - функция которую вызываем при onchange

    Пример подключения
    @include('_chunks/_pagination',['class'=>'pull-right','callback'=>'loadItems'])

--}}


<div class="block-inner">

    @if(isset($statistic))
        <div id="statistic" style="display: inline-block;">
            {{-- JS --}}
        </div>
    @endif

    <div class="{{ isset($b_class) ?  $b_class :'pull-left' }}">
        <div class="filter-group">
            {{Form::select('page_count', isset($count_pagination) ? collect($count_pagination) : collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']),
            request()->has('page')?request()->has('page'):100, ['class' => 'form-control select2-all',
            'id'=>'page_count', 'onchange'=>'PageButtonFunction('.$callback.');'])}}
        </div>
    </div>

    @if( isset($pagination__off) == false )
        <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
        <div style="margin-top: 12px;margin-left: 50%; display: inline-block">
            <span id="view_row"></span>/<span id="max_row"></span>
        </div>
    @endif

</div>

<script>

    var PAGE = 1;

    function PageButtonFunction(callback = false) {

        PAGE = 1;
        callback();

    }

    /*
    max - максимально число страниц int 10
    callback  - функция, которую вызвать после выбора страницы str loadItems
    scroll - скролл страницы вверх по умолчанию bool true / false
    */
    function ajaxPaginationUpdate(max = false, callback = false, scroll = true) {

        // console.log(vue_table.resetHead);
        if (typeof(vue_table) != "undefined" && vue_table)
            vue_table.resetHead();

        var offset = 80;
        if ($('#table_vue').length > 0)
            offset = ($('#table').offset()).top ? ($('#table').offset()).top - 80 : 80;

        $('#page_list').pagination({
            total: max,
            pageSize: 1,
            pageNumber: PAGE,
            layout: ['first', 'prev', 'links', 'next', 'last'],
            onSelectPage: function (pageNumber, pageSize) {
                PAGE = pageNumber;
                callback();
            },
            /*onInit: $('html, body').animate({scrollTop: offset}, 500)*/
        });
    }

</script>