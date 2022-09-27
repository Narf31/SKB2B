<div class="block-view">
    <h3>Страховые риски, страховые случаи</h3>
    <div class="row">


        <div class="col-sm-12">

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="20%">Раздел</th>
                        <th width="60%">Страховые риски, страховые случаи</th>
                        <th width="20%">Страховая сумма, руб.</th>
                    </tr>
                </thead>
                <tbody>


                @foreach($contract->product->flats_risks as $flats_risks)



                    <tr class="program programs_{{$flats_risks->id}}">
                        <th>
                            {{$flats_risks->title}}

                            <br/><br/>
                            {{ Form::checkbox("contract[risks][programs][$flats_risks->id]", 1, (array_search("$flats_risks->id", $terms) !== false ?1:0), ['class' => 'easyui-switchbutton programs', 'data-name'=>"programs_{$flats_risks->id}", 'data-options'=>"onText:'Да',offText:'Нет'", 'id' => "programs_{$flats_risks->id}" ]) }}
                            <br/><br/>

                        </th>

                        <th style="text-align: justify;">
                            {!! $flats_risks->risks_events !!}
                        </th>

                        <th><strong style="font-size: 16px;">{{titleFloatFormat($flats_risks->insurance_amount)}} руб. {{$flats_risks->insurance_amount_comment}}</strong></th>
                    </tr>

                @endforeach






                </tbody>
            </table>


        </div>


    </div>
</div>

<script>


    function initStartRisks(){

        stateViewRowProgram();

        setTimeout(function(){
            $('.switchbutton').click(function(){
                stateViewRowProgram()
            });
        }, 2000);


    }



    function stateViewRowProgram()
    {
        $(".program").css('background-color', '#fff');
        $(".programs").each(function( index ) {
           if($( this ).prop('checked')){
               $("."+$( this ).data('name')).css('background-color', '#e6ffe6');
           }
        });

    }


</script>