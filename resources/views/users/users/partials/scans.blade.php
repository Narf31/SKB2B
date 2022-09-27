<div class="row col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @if($user->scans->count())
        <table class="table orderStatusTable dataTable no-footer">
            <tbody>
                @foreach($user->scans as $file)

            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="upload-dot">
                    <div class="block-image">
                        @if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif']))
                        <a href="{{ url($file->url) }}" target="_blank">
                            <img class="media-object preview-image" src="{{ url($file->preview) }}" onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                        </a>
                        @else
                        <a href="{{ url($file->url) }}" target="_blank">
                            <img class="media-object preview-icon" src="/images/extensions/{{$file->ext}}.png">
                        </a>
                        @endif
                        <div class="upload-close">
                            <div class="" style="float:right;color:red;">
                                <a href="javascript:void(0);" onclick="removeScans('{{ $file->name }}')">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            </tbody>
        </table>
        @else
        <h3>{{ trans('form.empty') }}</h3>
        @endif
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {!! Form::open(['url'=>"/users/users/$user->id/scans",'method' => 'post', 'class' => 'dropzone', 'id' => 'addManyDocForm']) !!}
        <div class="dz-message" data-dz-message>
            <p>Перетащите сюда файлы</p>
            <p class="dz-link">или выберите с диска</p>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script>
    function removeScans(fileName) {
    if (!customConfirm()) {
    return false;
    }
    var filesUrl = '{{url("/users/users/{$user->id}/delete_scans")}}';
    var fileUrl = filesUrl + '/' + fileName;
    $.post(fileUrl, {
    _method: 'DELETE'
    }, function() {
    reload();
    });
    }
</script>