<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">

            <span onclick="openFancyBoxFrame('/directories/organizations/org_bank_account/create?org_id={{$organization->id}}')" class="btn btn-primary pull-right">
                {{ trans('form.buttons.add') }}
            </span>

            <table class="tov-table">
                <tbody>
                    <tr class="sort-row">
                        <th>{{ trans('organizations/org_bank_account.account_number') }}</th>
                        <th>Валюта</th>
                        <th>{{ trans('organizations/org_bank_account.bik') }}</th>
                        <th>{{ trans('organizations/org_bank_account.kur') }}</th>
                        <th>{{ trans('organizations/org_bank_account.bank') }}</th>
                        <th>{{ trans('organizations/org_bank_account.is_actual') }}</th>
                    </tr>
                    @if(sizeof($organization->bank_account))
                        @foreach($organization->bank_account as $bank_account)
                            <tr class="clickable-row" data-href="/directories/organizations/org_bank_account/{{ $bank_account->id }}/edit?org_id={{$organization->id}}">
                                <td>{{ $bank_account->account_number  }}</td>
                                <td>{{ \App\Models\Settings\Bank::CURRENCY[0/*$bank_account->account_currency_id*/]  }}</td>
                                <td>{{ $bank_account->bik  }}</td>
                                <td>{{ $bank_account->kur  }}</td>
                                <th>{{ $bank_account->bank_title  }}</th>
                                <th>{{ ($bank_account->is_actual) ? 'Да' : 'Нет'  }}</th>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>



    function initTab() {



    }




</script>