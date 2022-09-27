<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="page-subheading">
        <h2 class="inline-h1">Филиалы</h2>
        <a href="/directories/insurance_companies/{{$insurance_companies->id}}/bso_suppliers/0/"
           class="btn btn-primary pull-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>

    @if($insurance_companies->bso_suppliers)
        <table class="tov-table" >
            <tbody>
                <tr>
                    <th>Филиал</th>
                    <th>Город</th>
                    <th>Актуальность</th>
                </tr>
                @if(sizeof($insurance_companies->bso_suppliers))
                    @foreach($insurance_companies->bso_suppliers as $bso_suppliers)
                        <tr class="clickable-row" data-href="/directories/insurance_companies/{{$insurance_companies->id}}/bso_suppliers/{{$bso_suppliers->id}}/">
                            <td>{{ $bso_suppliers->title }}</td>
                            <td>{{ $bso_suppliers->city->title }}</td>
                            <td>{{ ($bso_suppliers->is_actual==1)? trans('form.yes') :trans('form.no')  }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @else
        {{ trans('form.empty') }}
    @endif



</div>