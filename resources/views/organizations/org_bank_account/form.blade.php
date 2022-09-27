<div class="col-md-12">

    <input type="hidden" name="org_id" id="org_id" value="{{request('org_id')}}"/>

    <div class="col-md-6">

@if ($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> {{ $error }}</span>
        </div>

    @endforeach
@endif

@if (session('success') && !count($errors))
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        {{ session('success') }}
    </div>
@endif

    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.is_actual') }}</label>
            <div class="col-sm-8">
                {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.account_number') }}</label>
            <div class="col-sm-8">
                {{ Form::text('account_number', old('account_number'), ['class' => 'form-control', 'required']) }}
            </div>
        </div>
       {{-- <div class="form-group">
            <label class="col-sm-4 control-label">Валюта</label>
            <div class="col-sm-8">
                {{ Form::select('account_currency_id', collect(\App\Models\Contracts\Route::CURRENCY), old('account_currency_id'), ['class' => 'form-control', '']) }}
            </div>
        </div>--}}
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.bik') }}</label>
            <div class="col-sm-8">
                {{ Form::text('bik', old('bik'), ['class' => 'form-control', '']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.bank') }}</label>
            <div class="col-sm-8">
                {{ Form::select('bank_id', $banks, old('bank_id'), ['class' => 'form-control', '']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">{{ trans('organizations/org_bank_account.kur') }}</label>
            <div class="col-sm-8">
                {{ Form::text('kur', old('kur'), ['class' => 'form-control', '']) }}
            </div>
        </div>

    </div>

</div>
</div>