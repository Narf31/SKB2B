<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3>{{$hold_kv_product->many_text}}</h3>
            <table class="table orderStatusTable dataTable no-footer">

                <tbody id="document-box">
                @if($contract->scans->count())
                @foreach($contract->scans as $file)
                    <div class="col-xs-4 col-sm-4 col-md-3 col-lg-2" id="document-{{$file->id}}">
                        <div class="upload-dot">
                            <div class="block-image">
                                @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG']))


                                    <div style="display: none; max-height: 550px" id="fs-galery{{$file->id}}">
                                        <a target="_blank" href="{{ url($file->url) }}">
                                        <img style="max-height: 550px;margin: 0 auto;" class="fs-galery"
                                             id="fs-galery{{$file->id}}" src="{{ url($file->url) }}"
                                             onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                                        </a>
                                        <p class="file_name col-xs-12 mt-15 text-center">
                                            {{ $file->original_name }}
                                            <br>
                                            <a href="{{ url($file->url) }}" target="_blank">Открыть оригинал</a>
                                            <a href="{{ url($file->url) }}" download>Скачать</a>

                                            <a id="rotate_right" href="#"><img src="/images/rotate_right.png" alt=""></a>
                                            <a id="rotate_left" href="#"><img src="/images/rotate_left.png" alt=""></a>
                                        </p>
                                    </div>

                                    <a class="iframe" rel="group" href="#fs-galery{{$file->id}}" rel="group">
                                        <img class="media-object preview-image" src="{{ ($file->preview)?url($file->preview):url($file->url) }}">
                                    </a>
                                    <p class="file_name col-xs-12 mt-15">
                                        <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                                    </p>

                                @elseif(in_array($file->ext, ['mp4', 'mkv', 'avi ','mov','avi','mpeg4','flv','3gpp']))

                                    <div style="display: none; max-height: 550px" id="fs-galery{{$file->id}}">
                                        <a target="_blank" href="{{ url($file->url) }}">
                                            <iframe style="width:640px; height:480px;" allowfullscreen src="{{ url($file->url) }}"></iframe>
                                        </a>
                                        <p class="file_name col-xs-12 mt-15 text-center">
                                            <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                                        </p>
                                    </div>

                                    <a class="iframe" rel="group" href="#fs-galery{{$file->id}}" rel="group">
                                        <img class="media-object preview-image" src="/images/extensions/video.png">
                                    </a>
                                    <p class="file_name col-xs-12 mt-15">
                                        <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                                    </p>

                                @else
                                    <div style="display: none; max-height: 550px" id="fs-galery{{$file->id}}">
                                        <a target="_blank"  href="{{ url($file->url) }}">
                                            <img style="max-height: 150px;margin: 0 auto;" class="fs-galery"
                                                 id="fs-galery{{$file->id}}" src="/images/extensions/{{mb_strtolower($file->ext)}}.png"
                                                 onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                                        </a>
                                        <p class="file_name col-xs-12 mt-15 text-center">
                                            <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                                        </p>
                                    </div>

                                    <a class="iframe" rel="group" href="#fs-galery{{$file->id}}" rel="group">
                                        <img class="media-object preview-icon"
                                             src="/images/extensions/{{mb_strtolower($file->ext)}}.png">
                                    </a>

                                    <p class="file_name col-xs-12 mt-15">
                                        <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                                    </p>

                                @endif
                                @if($is_delete == 1)
                                <div class="upload-close">
                                    <div class="" style="float:right;color:red;">
                                        <a href="javascript:void(0);" onclick="removeFile('{{ $file->name }}')">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach

                @endif
                </tbody>
            </table>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {!! Form::open(['url'=>"/contracts/actions/$contract->id/scans",'method' => 'post', 'class' => 'dropzone_ addManyDocForm']) !!}
        <div class="dz-message" data-dz-message>
            <p>Перетащите сюда файлы</p>
            <p class="dz-link">или выберите с диска</p>
        </div>
        {!! Form::close() !!}
    </div>
</div>

