
<div class="row form-horizontal" >
    <h2 class="inline-h1">Условия договора</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Дата заключения</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->sign_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата начала</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->begin_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата окончания</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->end_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Тип договора</span>
            <span class="view-value">{{collect([0=>"Первичный", 1=>'Пролонгация'])[$contract->is_prolongation]}}
                @if($contract->is_prolongation == 1) {{$contract->prolongation_bso_title}} @endif
                    </span>
        </div>


        <div class="clear"></div>



    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Правила</th>
            <th>Страховая сумма, руб.</th>
        </tr>
        </thead>
        <tbody>


        @foreach($contract->product->flats_risks as $flats_risks)

            @if(array_search("$flats_risks->id", $terms) !== false)
                <tr class="program programs_{{$flats_risks->id}}">
                    <td>{{$flats_risks->title}}</td>
                    <td><strong style="font-size: 16px;">{{titleFloatFormat($flats_risks->insurance_amount)}} руб. {{$flats_risks->insurance_amount_comment}}</strong></td>
                </tr>
            @endif
        @endforeach






        </tbody>
    </table>

</div>


<script>

    function initTerms() {





    }


    function initStartObject()
    {

    }


    function initStartRisks(){




    }



    function stateViewRowProgram()
    {

    }

</script>