@extends('layouts.app')


@section('content')





    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
        <h2>Дополнительные документы</h2>

        <div class="row form-group">
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if($special_settings->files->count())
                        <table class="table orderStatusTable dataTable no-footer">

                            <tbody>
                            @foreach($special_settings->files as $file)
                                <div class="col-lg-3">
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
                                                    <a href="javascript:void(0);" onclick="removeProductFile('{{ $file->name }}')">
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
                    {!! Form::open(['url'=>"/directories/products/{$product->id}/edit/special-settings/save_files",'method' => 'post', 'class' => 'dropzone', 'id' => 'addManyDocForm']) !!}
                    <div class="dz-message" data-dz-message>
                        <p>Перетащите сюда файлы</p>
                        <p class="dz-link">или выберите с диска</p>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>




    <br/>










@endsection



@section('js')

    <script>


        $(function () {



        });


        function removeProductFile(fileName) {
            if (!customConfirm()) {
                return false;
            }
            var filesUrl = '{{url("/directories/products/{$product->id}/edit/special-settings/delete-file")}}';
            var fileUrl = filesUrl + '/' + fileName;
            $.post(fileUrl, {
                _method: 'DELETE'
            }, function () {
                reload();

            });
        }


    </script>


@endsection