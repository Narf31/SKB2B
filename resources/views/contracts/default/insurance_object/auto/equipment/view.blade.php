
<div class="row form-horizontal" >
    <h2 class="inline-h1">Дополнительное оборудование</h2>
    <br/><br/>

    @foreach($equipments as $equipment)
        <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">{{$equipment->title}}</span>
                <span class="view-value">{{titleFloatFormat($equipment->payment_total)}}</span>
            </div>

            <div class="divider"></div><br/>
        </div>


    @endforeach


</div>





<script>


    function initStartInsureds(){


    }




</script>