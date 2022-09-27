<div class="col-lg-6 col-md-12" id="document-{{$file->id}}">
    <div class="upload-dot">
        <div class="block-image">
            @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif']))
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
                    <img class="media-object preview-image" src="{{ url($file->preview) }}">
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
                             id="fs-galery{{$file->id}}" src="/images/extensions/{{$file->ext}}.png"
                             onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                    </a>
                    <p class="file_name col-xs-12 mt-15 text-center">
                        <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                    </p>
                </div>

                <a class="iframe" rel="group" href="#fs-galery{{$file->id}}" rel="group">
                    <img class="media-object preview-icon"
                         src="/images/extensions/{{$file->ext}}.png">
                </a>

                <p class="file_name col-xs-12 mt-15">
                    <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                </p>

            @endif
            <div class="upload-close">
                <div class="" style="float:right;color:red;">
                    <a href="javascript:void(0);" onclick="removeFile('{{ $file->name }}')">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>