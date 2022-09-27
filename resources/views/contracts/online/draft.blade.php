<div class="page-heading">
    <h2 class="inline-h1">Черновики
        <div style="display: none;">
        <span id="view_row">0</span>/<span id="max_row">0</span></div>
    </h2>
</div>

<div class="row form-horizontal" style="margin-top: 15px"  >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="event_form" style="display: none;">
                    <span class="btn btn-danger pull-left" onclick="deleteDraft()">Удалить</span>
                </div>

            <br/><br/>
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div id="draft_table"></div>
                <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
            </div>

        </div>
    </div>
</div>

<script>

    var PAGE = 1;

    function initDraft() {


        loadItems();

    }


    function loadItems() {
        activePagination(0, 0, 1);

        $('#draft_table').html('');


        loaderShow();
        $.post("{{url("/contracts/online/")}}", {PAGE: PAGE}, function (response) {


            activePagination(response.view_row, response.max_row, response.page_max);
            $('#draft_table').html(response.html);


        }).done(function() {
            //loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });

    }

    function activePagination(view_row, max_row, pages) {

        $('#view_row').html(view_row);
        $('#max_row').html(max_row);

        $('#page_list').pagination({
            total: pages,
            pageSize: 1,
            pageNumber: PAGE,
            layout: ['first', 'prev', 'links', 'next', 'last'],
            onSelectPage: function (pageNumber, pageSize) {
                PAGE = pageNumber;
                loadItems()
            },

        });
    }


    function deleteDraft() {

        if(!customConfirm()){
            return false;
        }


        loaderShow();
        $.post("/contracts/online/draft-delete", {items:getCheckedOptions()}, function (response) {
            resetCheckedOptions();
            loadItems();
            loaderHide();

        }).always(function() {
            loaderHide();
        });

    }

</script>