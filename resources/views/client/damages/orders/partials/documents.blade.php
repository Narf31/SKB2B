<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">

        <span>Документы / Сканы</span>


    </div>


<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">

    @if($view == 'edit')
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">
            {!! Form::open(['url'=>$url_scan,'method' => 'post', 'class' => 'dropzone_', 'id' => 'addManyDocForm']) !!}
            <div class="dz-message" data-dz-message>
                <p>Перетащите сюда файлы</p>
                <p class="dz-link">или выберите с диска</p>
            </div>
            {!! Form::close() !!}
            <br/><br/>
        </div>
    @endif



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom" id="document-box">
        <div class="row row__custom justify-content-between">
        @if($order->scans->count())
            @foreach($order->scans as $file)
                @include('client.damages.orders.partials.document_view', ['file' => $file])
            @endforeach
        @endif
        </div>
    </div>




</div>

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
                    reload();
                });
            }

        });
        @endif
    }




</script>