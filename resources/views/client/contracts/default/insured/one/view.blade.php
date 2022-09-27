<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        Застрахованный
    </div>
    <div class="calc__menu">
        <ul>
            <li>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom" >
                    <div class="form__field" style="margin-top: 5px;">
                        <div class="checkbox__title">
                            {{$insurer->title}} ({{collect([0=>"муж.", 1=>'жен.'])[$insurer->sex]}})
                        </div>
                        <div class="checkbox__title" style="text-align: right;margin-top: -25px;margin-bottom: 10px;">
                            {{setDateTimeFormatRu($insurer->birthdate, 1)}}
                        </div>

                    </div>
                </div>
            </li>



        </ul>
    </div>
</div>





<script>


    function initStartInsureds(){


    }




</script>