<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

    @if(isset($hold_kv_product) && sizeof($hold_kv_product->documents))

    @foreach($hold_kv_product->documents->whereIn('program_id',[$contract->program_id ?:0,0]) as $key => $document)

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3" style="min-height: 180px;">

        <div class="row">
            <span class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px;">{{$document->file_title}} {!!$document->is_required && (int)$contract->prolongation_bso_id == 0 ? '<span class="required">*</span>' : ''!!}</span>

            @if($contract->document($document->id))

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="upload-dot">
                    <div class="block-image">
                        @if (in_array($contract->document($document->id)->file->ext, ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG']))
                        <a href="{{ url($contract->document($document->id)->file->url) }}" target="_blank">
                            <img class="media-object preview-image" src="{{ url($contract->document($document->id)->file->preview) }}" onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                        </a>
                        @else
                        <a href="{{ url($contract->document($document->id)->file->url) }}" target="_blank">
                            <img class="media-object preview-icon" src="/images/extensions/{{mb_strtolower($contract->document($document->id)->file->ext)}}.png">
                        </a>
                        @endif

                        @if($is_delete == 1)
                        <div class="upload-close">
                            <div class="" style="float:right;color:red;">
                                <a href="javascript:void(0);" onclick="removeDocument('{{ $contract->document($document->id)->file->name }}','{{$document->id}}')">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>


            @else


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
                {!! Form::open(['url'=>url("/contracts/actions/{$contract->id}/document/{$document->id}"),'method' => 'post', 'class' => 'addManyDocForm dropzone_' ]) !!}
                <div class="dz-message" data-dz-message>
                    <p>Перетащите сюда файл</p>
                    <p class="dz-link">или выберите с диска</p>
                </div>
                {!! Form::close() !!}
            </div>

            @endif
        </div>
    </div>


    @endforeach

    @endif

    <script>
        function removeDocument(fileName, documentId) {
            if (!customConfirm()) {
                return false;
            }
            var filesUrl = '{{ url("/contracts/actions/{$contract->id}/document/") }}';
            var fileUrl = filesUrl + '/' + documentId;
            $.post(fileUrl, {
                _method: 'DELETE'
            }, function () {

                @if(isset($type) && $type == 'edit')
                loaderShow();
                $.post('/contracts/online/{{$contract->id}}/save', $('#product_form').serialize(), function (response) {
                    reload();
                }).always(function () {
                    loaderHide();
                });
                @else
                     reload();
                @endif

            });
        }
    </script>

</div>