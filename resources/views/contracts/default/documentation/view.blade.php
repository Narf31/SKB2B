@if($contract->bso_supplier && $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id))

    @if(((int)$hold_kv_product->is_many_files == 1 || sizeof($hold_kv_product->documents->whereIn('program_id',[$contract->program_id ?:0,0]))))


        <div class="row form-horizontal">
            <h2 class="inline-h1">Документы</h2>
            <br/><br/>


            <div class="form-horizontal">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row form-horizontal">
                        @include(
                           'contracts.default.documentation.partials.document',
                           ['contract' => $contract, 'is_delete' => 0, 'hold_kv_product' => $contract->bso_supplier->hold_kv_product($contract->product_id)]
                        )
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
