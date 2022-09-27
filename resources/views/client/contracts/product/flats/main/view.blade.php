


        <div class="product_form row" style="padding-left: 15px;">



            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">



                <div class="row row__custom justify-content-between">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12 col__custom">
                        {{--Условия договора--}}
                        @include('client.contracts.default.terms.default.view', [
                            'contract'=>$contract,
                        ])
                    </div>
                </div>

                <div class="row row__custom justify-content-between">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">

                        @include('client.contracts.default.payments.view', [
                            "contract" => $contract,
                            "payment_link" => $contract->getPaymentsFirstInvoiceLink(),
                            'payments' => $contract->payments
                        ])

                    </div>
                </div>

                @if(sizeof($contract->masks))
                    <div class="row row__custom justify-content-between">
                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">
                            @include('client.contracts.default.documents.view', [
                                "contract" => $contract,
                                "masks" => $contract->masks
                            ])


                        </div>
                    </div>
                @endif


            </div>



            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

                <div class="row row__custom justify-content-between">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">
                    {{--Страхователь--}}
                    @include('client.contracts.default.subject.view', [
                        'subject_title' => 'Страхователь',
                        'subject_name' => 'insurer',
                        'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
                    ])
                    </div>
                </div>


                <div class="row row__custom justify-content-between">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom">
                        {{--Территория страхования--}}
                        @include('client.contracts.default.insurance_object.realty.flats.view', [
                            'object'=>$contract->object_insurer_flats,
                            'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):null,
                        ])
                    </div>
                </div>

            </div>

        </div>
