<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
    {!! Form::open(['url'=>"/users/users/$user->id/scans",'method' => 'post', 'class' => 'dropzone', 'id' => 'addPhotosForm']) !!}
    {!! Form::close() !!}
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

    <div class="block-main">
        @if($user->scans->count())
            <table class="table orderStatusTable dataTable no-footer">
                <thead>
                <tr>
                    <th>{{ trans('users/users.edit.title') }}</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->scans as $file)
                    <tr>
                        <td>{{ $file->original_name }}</td>
                        <td>
                            <a href="{{ url($file->url) }}" class="btn btn-primary" target="_blank">
                                {{ trans('form.buttons.download') }}
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-danger" type="button" onclick="removeFile('{{ $file->name }}')">
                                {{ trans('form.buttons.delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="col-md-12">
                <h3>{{ trans('form.empty') }}</h3>
            </div>
        @endif
    </div>


</div>



@section('js')
    <script>
        Dropzone.options.addPhotosForm = {
            paramName: 'scan',
            maxFilesize: 1000,
            //acceptedFiles: "image/*",
            init: function () {
                this.on("complete", function () {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        location.reload();
                    }

                });
            }
        };
    </script>
@append
