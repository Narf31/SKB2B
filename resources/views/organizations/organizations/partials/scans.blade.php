<div class="block-main col-xs-12 col-sm-12 col-md-12 col-lg-6">
    @if($organization->scans->count())
    <table class="table orderStatusTable dataTable no-footer">
        <div class="row" style="padding-top:30px;">
            @foreach($organization->scans as $file)
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
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
                                <a href="javascript:void(0);" onclick="removeOrgScans('{{ $file->name }}')">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!--<tr>
        <td>
            <a href="{{ url($file->url) }}"  target="_blank">
                {{ $file->original_name }}
            </a>
        </td>
        @if(auth()->user()->hasPermission('directories', 'organizations_edit'))

        <td>
            <button class="btn btn-danger" type="button" onclick="removeFile('{{ $file->name }}', 1)">
                {{ trans('form.buttons.delete') }}
            </button>
        </td>
        @endif
    </tr>-->
            @endforeach
        </div>
        </tbody>
    </table>
    @else

    <center><h3>{{ trans('form.empty') }}</h3></center>
    @endif
</div>



<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    {!! Form::open(['url'=>"/directories/organizations/organizations/$organization->id/scans",'method' => 'post', 'class' => 'dropzone', 'id' => 'addOrgDocForm']) !!}
    <div class="dz-message" data-dz-message>
        <p>Перетащите сюда файлы</p>
        <p class="dz-link">или выберите с диска</p>
    </div>
    {!! Form::close() !!}

</div>


<script>
    function removeOrgScans(fileName) {
    if (!customConfirm()) {
    return false;
    }
    var filesUrl = '{{url("/directories/organizations/organizations/{$organization->id}/delete_scans")}}';
    var fileUrl = filesUrl + '/' + fileName;
    $.post(fileUrl, {
    _method: 'DELETE'
    }, function() {
    reload();
    });
    }
</script>