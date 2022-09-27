<div class="row" id="actions" style="display: none">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <a id="delete_items" class="btn btn-danger btn-right">Удалить выбранные</a>
    </div>
</div>
@include("payments.reports.payments", ["payments"=>$act->payments])