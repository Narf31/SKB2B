@php($active_step = (auth()->guard('client')->check())?3:1)

<div class="row row__custom justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom">
        <div class="calc__box calc__item">
            <div class="calc__steps-label">
                Шаг <span class="steps__current">1</span> из <span class="steps__count">4</span>
            </div>
            <div class="calc__progress">
                <div class="bar" style="width: 10px;"></div>
            </div>
            <div class="calc__steps">

                <form id="product_form" class="product_form">


                {{--Страхователь (2 шага)--}}
                @include('client.contracts.default.subject.edit', [
                    'subject_title' => 'Страхователь',
                    'subject_name' => 'insurer',
                    'start_step' => 1,
                    'is_contact' => false,
                    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
                ])

                <input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>


                {{--Застрахованный--}}
                @include('client.contracts.default.insurance_object.realty.flats.edit', [
                    'start_step' => 3,
                    'object'=>(isset($contract->object_insurer_flats))?$contract->object_insurer_flats:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats(),
                    'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):[],
                ])



                {{--Оплата договора--}}
                @include('client.contracts.default.payments.release', [
                    'subject_title' => 'Страхователь',
                    'subject_name' => 'insurer',
                    'start_step' => 4,
                    'payments_type' => [4=>'Оплата картой'],
                    'default_type' => 4,
                    'contract'=>$contract,
                    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
                ])



                </form>

            </div>

        </div>
    </div>
</div>




@section('js')

    <script>
        var CALC_STEP = 3;
        var IS_CALC = 0;
    </script>

    @include('client.contracts.product.flats.js')
@stop

