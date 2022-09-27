@foreach(\App\Models\Contracts\ContractsCalculation::where('contract_id', $contract->id)->get() as $key => $calculation)


    @if((int)$calculation->state_calc == 1)


        @php
            $result = json_decode($calculation->json);
            $tariff = 0;

        @endphp


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <a href="javascript:void(0)" class="order-item" onclick="setSelectCalc('{{$calculation->id}}')">
                <div class="order-title"><span class="order-number">{{$key+1}}</span><span class="status circle">{{$calculation->program->title}}</span></div>
                <div class="divider"></div>
                <div class="order-contacts">

                    @foreach($result->info as $info)
                        @if(isset($info->title) && $info->title == $calculation->program->title)
                            @php
                                $tariff = titleFloatFormat($info->tariff);
                            @endphp
                        @endif
                        <div class="title">{{$info->title}} - {{$info->tariff}}</div>
                        <div class="name">{{titleFloatFormat($info->payment_total)}}</div>

                    @endforeach

                </div>
                <div class="divider"></div>
                <div class="discount-desc">
                    <div class="title">Расшифровка тарифа</div>
                    <span class="value">{{isset($result->base) ? $result->base->title : ''}}
                        {{isset($result->coefficient) ? $result->coefficient->title : ''}}
                        {{isset($result->equipment) ? $result->equipment->title : ''}}
                        {{isset($result->service) ? $result->service->title : ''}}</span>
                </div>
                <div class="divider"></div>
                <div class="order-summary">
                    <div class="discount">
                        <div class="title">Тариф</div>
                        <span class="value">{{$tariff}}</span>
                    </div>
                    <div class="total">
                        <div class="title">Страховая премия {{\App\Models\Directories\Products\Data\Kasko\Standard::INS_YEAR[$contract->data->insurance_term]}}</div>
                        <span class="value">{{titleFloatFormat($calculation->sum)}}</span>
                    </div>
                </div>
            </a>
        </div>






    @elseif((int)$calculation->state_calc == 0)


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
            <span class="order-item">
                <div class="order-title"><span class="order-number">{{$key+1}}</span><span class="status circle">{{$calculation->program->title}}</span></div>
                <div class="divider"></div>
                <div class="order-info">
                    <h2 style="color: red;">{{$calculation->messages}}</h2>
                </div>

            </span>
        </div>


    @endif

@endforeach


<script>



    function setSelectCalc(calculation_id)
    {
        loaderShow();

        $.get('/contracts/online/{{$contract->id}}/calculation', {calc:calculation_id}, function (response) {


            reload();


        }).always(function () {
            loaderHide();
        });
    }




</script>

