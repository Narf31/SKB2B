<div class="row  form-horizontal">


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" @if($subject_name == 'insurer') data-intro='Выберите организацию из списка' @endif>
            <label class="control-label">Название компании<span class="required">*</span></label>
            {{ Form::text("contract[{$subject_name}][title]", $subject->title, ['class' => 'form-control valid_accept', 'id'=>"{$subject_name}_title"]) }}
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">ИНН<span class="required">*</span></label>
            {{ Form::text("contract[{$subject_name}][inn]", $subject->inn, ['class' => 'form-control valid_accept', 'id'=>"{$subject_name}_inn"]) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">ОГРН</label>
            {{ Form::text("contract[{$subject_name}][ogrn]", $subject->ogrn, ['class' => 'form-control', 'id'=>"{$subject_name}_ogrn"]) }}
        </div>

        <div class="clear"></div>




    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

        @if($subject->general && (int)$subject->general->export_id > 0)
            <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @include("contracts.default.subject.partials.view.gentral_1", ['general' => $subject->general])
            </div>


        @else
            @if(strlen($subject->title) > 0)
                <h1 style="color: #ff9999;">Отсутствует анкета, необходимо провести процедуру идентификации 115</h1>
                <input type="hidden" class="valid_accept" value=""/>
            @endif
        @endif


        <div class="clear"></div>



    </div>





</div>


<script>



    function initStartSubject()
    {
        $('#{{$subject_name}}_title, #{{$subject_name}}_inn').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;

                $('#{{$subject_name}}_title').val(suggestion.value);
                $('#{{$subject_name}}_inn').val(data.inn);
                $('#{{$subject_name}}_ogrn').val(data.ogrn);

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');



                loaderShow();

                $.get("{{url("/contracts/online/{$contract->id}/action/subject/search/ul/?name={$subject_name}")}}", {title:suggestion.value, inn:data.inn,
                    ogrn:data.ogrn}, function (response)  {

                    $('#{{$subject_name}}_type').change();


                }).always(function () {
                    loaderHide();

                });

            }
        });



        initSelect2();
        formatDate();

    }



</script>




