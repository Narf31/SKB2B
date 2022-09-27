<?php

namespace App\Http\Controllers\Directories\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organizations\OrgBankAccount;
use App\Models\Settings\Bank;
use Illuminate\Http\Request;

class OrgBankAccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:directories,organizations');
    }


    public function create()
    {
        return view('organizations.org_bank_account.create', [
            'banks' => Bank::where('is_actual', 1)->get()->pluck('title', 'id'),
        ]);
    }

    public function edit($id)
    {
        return view('organizations.org_bank_account.edit', [
            'org_bank_account' => OrgBankAccount::findOrFail($id),
            'banks'            => Bank::where('is_actual', 1)->get()->pluck('title', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save(new OrgBankAccount, $request);
    }

    public function update($id, Request $request)
    {
        return $this->save(OrgBankAccount::findOrFail($id), $request);
    }


    private function save(OrgBankAccount $org_bank_account, Request $request)
    {

        $org_bank_account->is_actual      = (int)$request->is_actual;
        $org_bank_account->account_number = $request->account_number;
        //$org_bank_account->account_currency_id        = (int)$request->account_currency_id;
        $org_bank_account->org_id         = (int)$request->org_id;
        $org_bank_account->bank_id        = (int)$request->bank_id;
        $org_bank_account->bik            = $request->bik;
        $org_bank_account->kur            = $request->kur;
        $org_bank_account->bank_title     = Bank::findOrFail((int)$request->bank_id)->title;


        $org_bank_account->save();
        //return redirect("/organizations/org_bank_account/{$org_bank_account->id}/edit/");
        return parentReloadTab();
    }

    public function destroy($id)
    {
        OrgBankAccount::findOrFail($id)->delete();

        return response('', 200);
    }

}
