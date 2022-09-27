<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col__custom" id="document-{{$file->id}}">
    <div class="upload-dot">
        <div class="block-image">
            @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG']))


                <a class="iframe" rel="group" href="{{ url($file->url) }}" target="_blank" rel="group">
                    <img class="media-object preview-image" src="{{ ($file->preview)?url($file->preview):url($file->url) }}">
                </a>


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


            @else
                <div style="display: none; max-height: 550px" id="fs-galery{{$file->id}}">
                    <a href="{{ url($file->url) }}" target="_blank">
                        <img style="max-height: 150px;margin: 0 auto;" class="fs-galery"
                             id="fs-galery{{$file->id}}" src="/images/extensions/{{mb_strtolower($file->ext)}}.png"
                             onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                    </a>
                    <p class="file_name col-xs-12 mt-15 text-center">
                        <a href="{{ url($file->url) }}" download>{{ $file->original_name }}</a>
                    </p>
                </div>

                <a class="iframe" rel="group" href="{{ url($file->url) }}" target="_blank" rel="group">
                    <img class="media-object preview-icon"
                         src="/images/extensions/{{mb_strtolower($file->ext)}}.png">
                </a>



            @endif
            @if($view == 'edit')
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