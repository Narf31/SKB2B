<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="row page-heading">
        <h2 class="inline-h1">{{$json['programs'][$program_slug]['title']}}</h2>
    </div>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="row table">
            <thead>
            <tr>
                <th width="180px;" nowrap>{{$json['programs'][$program_slug]['categorys_tab_title']}}</th>
                @foreach($json['programs'][$program_slug]['categorys'][0] as $param1_id => $param1)
                    @if($param1_id > 0)
                    <th>{{$param1}}</th>
                    @endif
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($json['programs'][$program_slug]['categorys'][1] as $param2_id => $param2)

                <tr>
                    <td nowrap><span class="hidden">{{$param2_id}}</span>{{$param2}}</td>
                    @foreach($json['programs'][$program_slug]['categorys'][0] as $param1_id => $param1)
                        @if($param1_id > 0)
                        <td>
                            @php
                                $_value = '';
                                if(isset($json_data['programs']) && isset($json_data['programs'][$program_slug])){
                                    $_value = \App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager::getTariffValue($json_data['programs'][$program_slug]['values'], $param1_id, $param2_id);
                                }
                            @endphp

                            {{Form::text("value[{$param1_id}][{$param2_id}]", $_value, ['class' => 'sum', 'style'=>'width: 80px'])}}
                        </td>
                        @endif
                    @endforeach
                </tr>

            @endforeach
            </tbody>
        </table>


    </div>
    <br/>

</div>
