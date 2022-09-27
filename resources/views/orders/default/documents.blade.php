<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="document-box">

        @if($order->scans->count())
            @foreach($order->scans as $file)
                @include('orders.default.partials.documents', ['file' => $file])
            @endforeach
        @endif
    </div>

    @if($view == 'edit')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {!! Form::open(['url'=>$url_scan,'method' => 'post', 'class' => 'dropzone_', 'id' => 'addManyDocForm']) !!}
        <div class="dz-message" data-dz-message>
            <p>Перетащите сюда файлы</p>
            <p class="dz-link">или выберите с диска</p>
        </div>
        {!! Form::close() !!}
    </div>
    @endif
</div>

<script>

    function initDocuments() {
        @if($view == 'edit')
        $("#addManyDocForm").dropzone({
            paramName: 'file',
            maxFilesize: 1000,
            acceptedFiles: ".png, .jpg, .jpeg, .txt, .csv, .mp4, .mkv, .avi ,.mov,.avi,.mpeg4,.flv,.3gpp,image/*, .xls, .xlsx, .pdf, .doc, .docx",

            init: function () {
                this.on("queuecomplete", function () {
                    selectTab(TAB_INDEX);
                });
            }

        });
        @endif
    }




</script>