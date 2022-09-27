
<div class="row form-horizontal" >
    <h2 class="inline-h1">Застрахованный</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">ФИО</span>
            <span class="view-value">{{$insurer->title}} ({{collect([0=>"муж.", 1=>'жен.'])[$insurer->sex]}})</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата рождения</span>
            <span class="view-value">{{setDateTimeFormatRu($insurer->birthdate, 1)}}</span>
        </div>

        <div class="clear"></div>
    </div>
</div>





<script>


    function initStartInsureds(){


    }




</script>