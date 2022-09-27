@extends('layouts.app')


@section('content')


    @if(isset($request->program) && $request->program >= 0)

        @include('directories.products.special_settings.tariff.vzr.index', [
          'url'=>url("/directories/organizations/organizations/{$organization->id}/tariff/{$product->id}"),
          'json'=>(\App\Processes\Tariff\Settings\Product\TariffVzr::defaultJson()),
          'json_data'=>($json?:\App\Processes\Tariff\Settings\Product\TariffVzr::defaultJson())
        ])

    @else

        <div class="row form-horizontal col-xs-12 col-sm-12 col-md-4 col-lg-3">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Тип</label>
                    <div class="col-sm-8">
                        {{ Form::select('settings_id', collect([0=>'По умолчанию', 1=>'Уникальный']), $special_settings->settings, ['class' => 'form-control select2-ws', 'id'=>'settings_id']) }}
                    </div>
                </div>

                <span onclick="setSettingsTariff()" class="btn btn-primary pull-left">Применить</span>

        </div>

        @if($special_settings->settings == 1)

            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9">

            @include('directories.products.special_settings.tariff.vzr.index', [
              'url'=>url("/directories/organizations/organizations/{$organization->id}/tariff/{$product->id}"),
              'json'=>(\App\Processes\Tariff\Settings\Product\TariffVzr::defaultJson()),
              'json_data'=>($json?:\App\Processes\Tariff\Settings\Product\TariffVzr::defaultJson())
            ])

            </div>

        @endif

    @endif





@endsection



@section('js')

    <script>


        $(function () {

            @if(isset($request->program) && $request->program >= 0)
                initActivTable();
            @endif

        });


        function setSettingsTariff() {
            openPage("/directories/organizations/organizations/{{$organization->id}}/tariff/{{$product->id}}?settings="+$("#settings_id").val())
        }


    </script>


@endsection