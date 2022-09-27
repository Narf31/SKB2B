<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Алгоритмы рассрочки</h2>
        <a href="/directories/insurance_companies/{{$insurance_companies->id}}/installment_algorithms/0/"
           class="fancybox fancybox.iframe btn btn-primary pull-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if($insurance_companies->algorithms)
                <table class="tov-table" >

                    @if(sizeof($insurance_companies->algorithms))
                        @foreach($insurance_companies->algorithms as $algorithms)
                            <tr href="/directories/insurance_companies/{{$insurance_companies->id}}/installment_algorithms/{{$algorithms->id}}/" class="clickable-row fancybox fancybox.iframe">
                                <td>{{ \App\Models\Directories\InstallmentAlgorithms::ALG_TYPE[$algorithms->algorithm_id]  }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            @else
            {{ trans('form.empty') }}
            @endif
        </div>
    </div>
</div>