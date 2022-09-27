@extends('layouts.app')


@section('content')

    @if($matching->status_id == 0 || $matching->status_id == 1|| $matching->status_id == 3)

    <div class="page-heading">
        <h2 class="inline-h1">Служба безопасности</h2>
    </div>


    <div class="row form-horizontal" style="margin-top: 15px">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">



                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <span class="btn btn-danger pull-left" onclick="setStatusMatching(5)">Запрет</span>

                        <span class="btn btn-success pull-right" onclick="setStatusMatching(4)">Акцепт</span>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                        <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="field form-col">
                                <div>
                                    @if(View::exists("matching.default.product.{$matching->contract->product->slug}"))
                                        @include("matching.default.product.{$matching->contract->product->slug}", ['contract'=>$matching->contract])
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label" style="width: 100%;max-width: none;">
                                        Примечание
                                    </label>
                                    {{ Form::textarea("comments", $matching->comments, ['class' => 'form-control', 'id'=>'comments']) }}
                                </div>
                            </div>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>



        function setStatusMatching(state) {


            Swal.fire({
                title: 'Вы уверены?',
                showCancelButton: true,
                confirmButtonText: 'Да',
                cancelButtonText: 'Отмена',
                showLoaderOnConfirm: true,

                }).then((result) => {
                    if (result.value)
                    {
                        saveWork(state);
                    }
            });


        }

        function saveWork(state) {


            loaderShow();

            $.post("/matching/security-service/{{$matching->id}}/set-status", {state:state, comments:$("#comments").val()}, function (response) {
                loaderHide();

                if (Boolean(response.state) === true) {

                    flashMessage('success', "Данные успешно сохранены!");
                    reload();

                }else {
                    flashHeaderMessage(response.msg, 'danger');

                }

            }).done(function() {
                loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });



        }


    </script>

    @endif

    @if($matching->contract)

    <div class="page-heading">
        <h2 class="inline-h1">Договор</h2>
    </div>

    <div class="row form-horizontal" style="margin-top: 15px">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">

                    @include("contracts.product.{$matching->contract->product->slug}.main.view", ['contract'=>$matching->contract, 'type'=>'view', 'is_matching' => true])


                </div>
            </div>
        </div>
    </div>

    @endif


@stop
