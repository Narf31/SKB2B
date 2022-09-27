<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <a href="/finance/invoice/reservation/create" class="btn btn-success btn-right">Создать</a>
    <div class="row form-group">
        @if(auth()->user()->role->rolesVisibility(7)->visibility != 2)
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <label class="control-label">Агент</label>
                @php($agents_select = auth()->user()->visibleUsers('finance')->get()->pluck('name', 'id'))
                @php($agent_selected = request('agent_id') ? request()->query('agent_id') : auth()->user()->id)
                {{ Form::select('agent_id', $agents_select, $agent_selected, ['class' => 'form-control select2 select2-all', 'id'=>'agent_id', 'required', 'onchange' => 'loadItems()']) }}
            </div>
        @else
            {{Form::hidden('agent_id', auth()->user()->id)}}
        @endif
    </div>
</div>

<div id="table"></div>




<script>

     var PAGE = 1;

    function setPage(field) {
        PAGE = field;
        loadItems();
    }


    $(function () {

        $(document).on('click', '.delete-reservation', function(){
            if(confirm('Удалить резервирование?')){
                var THIS = $(this);
                $.post(THIS.attr('data-href'), {}, function(res){
                    flashMessage('success', res.msg);
                    THIS.closest('tr').remove()
                });
            }
        });

        loadItems();

    });


    function loadItems(){
        var data = getData();
        loaderShow();
        $.post("{{url("/finance/invoice/reservation/get_reservation_table")}}", data, function (response) {
            $('#table').html(response.html);
        }).fail(function(){
            $('#table').html('');
        }).always(function() {
            loaderHide();
        });

    }

    function getData(){
        return {
            agent_id: $('[name="agent_id"]').val(),
            type: $('[name="type"]').val(),
            pageCount: $("#pageCount").val(),
            page: PAGE,
        }
    }


</script>