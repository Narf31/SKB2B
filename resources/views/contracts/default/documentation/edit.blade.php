
@if($contract->bso_supplier && $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id))

    @if(((int)$hold_kv_product->is_many_files == 1 || sizeof($hold_kv_product->documents->whereIn('program_id',[$contract->program_id ?:0,0]))))


        <div class="page-heading product_form">
            <h2 class="inline-h1">Документы</h2>
        </div>

        <div class="row form-horizontal" >
            <div class="block-main" style="border-right: none;">
                <div class="block-sub">
                    <div class="form-horizontal">
                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row form-horizontal">
                                @include(
                            'contracts.default.documentation.partials.document',
                            ['contract' => $contract, 'is_delete' => 1, 'hold_kv_product' => $contract->bso_supplier->hold_kv_product($contract->product_id)]
                        )
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <script>
            function initDocument() {

            }
        </script>

    @endif

@endif
