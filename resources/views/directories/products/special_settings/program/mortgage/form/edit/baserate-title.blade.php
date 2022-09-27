@extends('layouts.frame')

@section('title')

     Базовая Титул

@stop

@section('content')

     {{ Form::open(['url' => url("/directories/products/{$product_id}/edit/special-settings/mortgage/baserate-title/{$baserate_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


     <div class="form-group col-sm-6">
         <label class="col-sm-12 control-label">Объект страхования</label>
         <div class="col-sm-12">
             {{ Form::select('class_realty', \App\Models\Directories\Products\Data\Mortgage\Mortgage::CLASS_REALTY, $baserate->class_realty, ['class' => 'form-control select2-ws']) }}
         </div>
     </div>

     <div class="form-group col-sm-6">
         <label class="col-sm-12 control-label">Тип недвижимости</label>
         <div class="col-sm-12">
             {{ Form::select('type_realty', \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_REALTY, $baserate->type_realty, ['class' => 'form-control select2-ws']) }}
         </div>
     </div>

     <div class="form-group col-sm-6">
          <label class="col-sm-12 control-label">Тариф %</label>
          <div class="col-sm-12">
               {{ Form::text('tarife', titleFloatFormat($baserate->tarife, 0, 1, 3), ['class' => 'form-control sum']) }}
          </div>
     </div>






     {{Form::close()}}

@stop

@section('footer')


     @if($baserate_id > 0)
          <button class="btn btn-danger pull-left" onclick="deleteBaseRate()">{{ trans('form.buttons.delete') }}</button>

          <script>

              function deleteBaseRate() {
                  if (!customConfirm()) return false;

                  $.post('{{url("/directories/products/{$product_id}/edit/special-settings/mortgage/baserate-title/{$baserate_id}")}}', {
                      _method: 'delete'
                  }, function () {
                      window.parent.reloadTab();
                  });
              }

          </script>

     @endif

     <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>





@stop

@section('js')

     <script>

         $(function(){




         });


     </script>


@stop

