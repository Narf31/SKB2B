<form id="product_form" class="product_form" style="padding-top: 20px;">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="page-heading">
            <h2 class="inline-h1">Процедуры</h2>
        </div>

        <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row form-horizontal">

                <br/>

                @if($contract->data->general_insurer_id > 0)

                    @foreach($contract->data->general_insurer->procedures as $procedure)

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                        <span class="order-item">
                            <div class="order-title">
                                <span class="order">{{$procedure->title}}</span>
                            </div>

                            <div class="order-contacts">
                                <div class="name">{{ $procedure->organization_title }} {{ $procedure->inn }} {{ $procedure->ogrn }}</div>
                                <div class="name">{{ $procedure->address }}</div>
                            </div>


                            <div class="order-contacts">
                                {!! $procedure->content_html !!}
                            </div>

                        </span>

                        </div>



                    @endforeach

                @else

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h1>Выберите cтрахователя</h1>
                        </div>
                    </div>

                @endif




            </div>
        </div>
    </div>

</form>



<script>


    function initTab() {

    }

    function saveTab() {

    }

</script>