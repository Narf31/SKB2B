@extends('client.layouts.app')

@section('head')

@append


@section('content')


    <div class="product_form row" style="padding-left: 15px;">



        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="row row__custom justify-content-between">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12 col__custom">
                    @include('client.damages.orders.partials.info', ['damage'=>$damage])
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="row row__custom justify-content-between">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12 col__custom">
                    @include('client.damages.orders.partials.documents', [
                        'order'=>$damage,
                        'view' => ($damage->status_id < 3 && $damage->position_type_id == 2)?'edit':'view',
                        'url_scan' => urlClient("/damages/actions/scan/{$damage->id}/")
                    ])
                </div>
            </div>
        </div>

    </div>



@endsection


@section('js')

    <script>
        $(function () {

            initDocuments();

        });


        function customConfirm() {

            return confirm('{{trans('form.are_you_sure')}}');
        }

        function removeFile(fileName) {
            if (!customConfirm()) {
                return false;
            }
            var filesUrl = '{{ url(\App\Models\File::URL) }}';
            var fileUrl = filesUrl + '/' + fileName;
            $.post(fileUrl, {
                _method: 'DELETE'
            }, function () {
                reload();

            });
        }

    </script>

@endsection