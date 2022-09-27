
<div class="row form-horizontal" >
    <h2 class="inline-h1">Застрахованные</h2>
    <br/><br/>

    @foreach($insurers as $insurer)
        <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">{{$insurer->title}} ({{collect([0=>"муж.", 1=>'жен.'])[$insurer->sex]}})</span>
                <span class="view-value">{{setDateTimeFormatRu($insurer->birthdate, 1)}}</span>
            </div>

            <div class="clear"></div>
        </div>
    @endforeach


</div>





<script>


    function initStartInsureds(){


    }




</script>