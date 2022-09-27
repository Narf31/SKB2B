@extends('layouts.app')


@section('content')



    {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings/".(int)$risk->id."/risks"), 'class' => 'my_risk_form', 'method' => 'post', "autocomplete" =>"off"]) }}


    <div class="row">

        <div class="form-group">
            <label class="col-sm-12">Раздел</label>
            <div class="col-sm-12">
                {{ Form::text('title', $risk->title, ['class' => 'form-control', 'required']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Страховая премия</label>
            <label class="col-sm-3 control-label">Страховая сумма</label>
            <label class="col-sm-6 control-label">Страховая сумма примечания</label>

            <div class="col-sm-3">
                {{ Form::text('payment_total', titleFloatFormat($risk->payment_total), ['class' => 'form-control sum', 'required']) }}
            </div>

            <div class="col-sm-3">
                {{ Form::text('insurance_amount', titleFloatFormat($risk->insurance_amount), ['class' => 'form-control sum', 'required']) }}
            </div>
            <div class="col-sm-6">
                {{ Form::text('insurance_amount_comment', $risk->insurance_amount_comment, ['class' => 'form-control']) }}
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-12">Выгодоприобретатель</label>
            <div class="col-sm-12">
                {{ Form::text('beneficiary', $risk->beneficiary, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-12">Территория страхования</label>
            <div class="col-sm-12">
                {{ Form::text('insurance_territory', $risk->insurance_territory, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Объект страхования</label>
                    <div class="col-sm-12">
                <textarea id="insurance_object" type="text" class="form-control" name="insurance_object" >
                    {!! $risk->insurance_object !!}
                </textarea>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="form-group">
                    <label class="col-sm-12 control-label">Страховые риски, страховые случаи</label>
                    <div class="col-sm-12">
               <textarea id="risks_events" type="text" class="form-control" name="risks_events" >
                    {!! $risk->risks_events !!}
                </textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>



    {{ Form::close() }}


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
    <button onclick="submitForm()" type="submit" class="btn btn-primary pull-left">{{ trans('form.buttons.save') }}</button>

    @if((int)$risk->id > 0)
        <button class="btn btn-danger pull-right" onclick="deleteRisk()">{{ trans('form.buttons.delete') }}</button>
    @endif



@endsection



@section('js')

    <script src="/plugins/ckeditor/ckeditor.js"></script>
    <script>


        $(function () {
            CKEDITOR.replace('insurance_object');
            CKEDITOR.replace('risks_events');
        });


        function deleteRisk() {
            if (!customConfirm()) return false;

            $.post('{{url("/directories/products/{$product->id}/edit/special-settings/".(int)$risk->id."/risks")}}', {
                _method: 'delete'
            }, function () {
                openPage('{{url ("/directories/products/{$product->id}/edit/special-settings/")}}')
            });
        }

        function submitForm() {

            var success = true;
            var form = $('.my_risk_form');
            form.find('input[required=required], select[required=required]').each(function () {
                var valid = $(this).val() != '';
                $(this).toggleClass('has-error', !valid);
                if (!valid) {
                    success = false;
                }
            });

            if (success) {
                form.submit();
            }

        }


    </script>


@endsection