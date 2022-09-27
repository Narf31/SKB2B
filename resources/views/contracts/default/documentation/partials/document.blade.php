@if(isset($hold_kv_product) && (int)$hold_kv_product->is_many_files == 1)

    @include('contracts.default.documentation.partials.many_files_document', ['contract' => $contract, 'is_delete'=>$is_delete, 'hold_kv_product'=>$hold_kv_product])

    <br/>
@endif


@include('contracts.default.documentation.partials.files_document', ['contract' => $contract, 'is_delete'=>$is_delete, 'hold_kv_product'=>$hold_kv_product])


<script>

    function initDocument() {
        $(".addManyDocForm").dropzone({
            maxFilesize: 1000,
            init: function () {
                this.on("queuecomplete", function () {

                    @if(isset($type) && $type == 'edit')
                    loaderShow();
                    $.post('/contracts/online/{{$contract->id}}/save', $('#product_form').serialize(), function (response) {
                        location.reload();
                    }).always(function () {
                        loaderHide();
                    });
                    @else
                        location.reload();
                    @endif



                });
            }
        });
    }


</script>