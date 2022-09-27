<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Серия БСО</h2>
        <a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/0/"
           class="fancybox fancybox.iframe btn btn-primary pull-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if($insurance_companies->type_bso)
                <table class="tov-table" >
                    <thead>
                    <tr>
                        <th>Класс</th>
                        <th>Продукт</th>
                        <th>Серия</th>
                    </tr>
                    </thead>
                    @if(sizeof($insurance_companies->type_bso))
                        @foreach($insurance_companies->type_bso as $bso)
                            <tr href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{$bso->id}}/" class="clickable-row fancybox fancybox.iframe">
                                <td>{{ \App\Models\Directories\TypeBso::CLASS_BSO[$bso->bso_class_id]  }}</td>
                                <td>{{ ($bso->product)?$bso->product->title : 'Все'  }}</td>
                                <td>{{ $bso->bso_serie  }}</td>
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