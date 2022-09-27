<div class="page-heading">
    <h2 class="inline-h1">{{$subject_title}}
        @if($subject_name != 'insurer')


            @if($subject_name == 'beneficiar')

                {{ Form::radio("contract[$subject_name][is_subject]", 0, $is_beneficiar,['onclick' => "viewSubjectDataRadio_{$subject_name}()", 'class'=>"{$subject_name}_is_subject clear_offers"]) }} <span style="font-size: 13px;">другой</span>

                {{ Form::radio("contract[$subject_name][is_subject]", 1, $is_insurer,['onclick' => "viewSubjectDataRadio_{$subject_name}()", 'class'=>"{$subject_name}_is_subject clear_offers"]) }} <span style="font-size: 13px;">страхователь</span>

                {{ Form::radio("contract[$subject_name][is_subject]", 2, $is_owner,['onclick' => "viewSubjectDataRadio_{$subject_name}()", 'class'=>"{$subject_name}_is_subject clear_offers"]) }} <span style="font-size: 13px;">собственник</span>


            @else

                {{ Form::checkbox("contract[$subject_name][is_insurer]", 1, $is_insurer,['onclick' => "viewSubjectData_{$subject_name}()", 'id'=>"{$subject_name}_is_insurer", 'class'=>"not_valid clear_offers"]) }} <span style="font-size: 13px;">страхователь</span>

            @endif
        @endif



        @if(isset($set_type))
            <input type="hidden" value="{{$set_type}}" name="contract[{{$subject_name}}][type]" id="{{$subject_name}}_type" data-key="{{$subject_name}}"/>

        @else

            @php
                $select_type = [0=>"ФЛ", 3=>'ЮЛ'];
                if(isset($set_select_type)) $select_type = $set_select_type;
                if($subject->type == 1) $subject->type = 3;
            @endphp
            <div class="col-lg-1 pull-right {{$subject_name}}-view-type" >
                {{ Form::select("contract[$subject_name][type]", collect($select_type), $subject->type, ['class' => 'form-control select2-ws clear_offers', 'id'=>"{$subject_name}_type", 'data-key'=>"{$subject_name}"]) }}
            </div>
        @endif


    </h2>



    <div class="clear"></div>
</div>
<br/>
<div class="row form-equally form-horizontal" id="main_control_form_{{$subject_name}}" @if($subject_name == 'insurer') data-intro='Данные {{$subject_title}}!' @endif>
    <div class="form-horizontal">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="control_form_{{$subject_name}}">

        </div>

        <div class="clear"></div>

    </div>
</div>



<script>

    function initSubject_{{$subject_name}}()
    {
        $('#{{$subject_name}}_type').change(function () {

            loaderShow();
            $.get("{{url("/contracts/online/".(int)$contract->id)}}/action/subject?name={{$subject_name}}&general_document={{(isset($general_document)?$general_document:-1)}}&is_lat={{(isset($is_lat)?$is_lat:0)}}&type="+parseInt($(this).val()), {}, function (response)  {
                loaderHide();
                $("#control_form_{{$subject_name}}").html(response);
                initStartSubject();

                @if($subject_name == 'owner')
                    setTimeout('viewSubjectData_{{$subject_name}}()', 1000);

                @endif

                @if($subject_name == 'beneficiar')
                        setTimeout('viewSubjectDataRadio_{{$subject_name}}()', 1000);
                @endif

            })
                .done(function() {
                    loaderShow();
                })
                .fail(function() {
                    loaderHide();
                })
                .always(function() {
                    loaderHide();
                });


        });

        $('#{{$subject_name}}_type').change();




    }




    document.addEventListener("DOMContentLoaded", function (event) {
        initSubject_{{$subject_name}}();
    });


    function viewSubjectData_{{$subject_name}}()
    {
        if($('#{{$subject_name}}_is_insurer').prop('checked')){
            isView_{{$subject_name}}(1);
        }else{
            isView_{{$subject_name}}(0);
        }
    }


    function viewSubjectDataRadio_{{$subject_name}}()
    {
        is_subject = $('.{{$subject_name}}_is_subject:checked').val();
        if(is_subject > 0){
            isView_{{$subject_name}}(1);
        }else{
            isView_{{$subject_name}}(0);
        }

    }


    function isView_{{$subject_name}}(state)
    {
        if(state == 1){
            $("#main_control_form_{{$subject_name}}").hide();
            $(".{{$subject_name}}-view-type").hide();
            $('[name*="contract[{{$subject_name}}]"').removeClass('valid_accept');

        }else{
            $("#main_control_form_{{$subject_name}}").show();
            $(".{{$subject_name}}-view-type").show();
            $('[name*="contract[{{$subject_name}}]"').addClass('valid_accept');

            $('.not_valid').removeClass('valid_accept');
        }
    }



</script>

