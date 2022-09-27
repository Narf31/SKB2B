
@if($general->data->profession_id == 1)
    <div class="col-sm-12">
        <h2>Процедуры</h2>

    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        @foreach($general->procedures as $procedure)

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


    </div>

@endif

<script>
    

</script>