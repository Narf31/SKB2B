{{ Form::open(['url' => url("/general/subjects/edit/{$general->id}/podft"), 'method' => 'post', 'class' => 'row form-horizontal', 'id'=>'form-data']) }}

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="col-sm-12">
                        <h2>{{$general->title}}

                            @if($general->type_id == 0)
                                - {{setDateTimeFormatRu($general->data->birthdate, 1)}}
                            @endif
                        </h2>
                </div>


                @if($general->type_id == 0)
                    @include("general.subjects.info.fl.podft.{$state}")
                @else
                    @include("general.subjects.info.ul.podft.{$state}")
                @endif

                @if($state == 'edit')
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <span onclick="saveClients()" class="btn btn-primary pull-left">{{ trans('form.buttons.save') }}</span>
                        </div>
                    </div>
                @endif




            </div>
        </div>
    </div>
</div>


{{Form::close()}}



<script>

    function saveClients()
    {

        if(validate()){



            loaderShow();

            $.post('{{url("/general/subjects/edit/{$general->id}/podft")}}', $('#form-data').serialize(), function (response) {



                if (Boolean(response.state) === true) {

                    flashMessage('success', "Данные успешно сохранены!");

                }else {
                    if(response.errors){
                        $.each(response.errors, function (index, value) {
                            flashHeaderMessage(value, 'danger');
                            $('[name="' + index + '"]').addClass('form-error');
                        });
                    }else{
                        flashHeaderMessage(response.msg, 'danger');
                    }

                }

            }).always(function () {
                loaderHide();
            });

        }

    }


    function startMainFunctions() {

        initSelect2();
        initDataSubjects();
    }




</script>