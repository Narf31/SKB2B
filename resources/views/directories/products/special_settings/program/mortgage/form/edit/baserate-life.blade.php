@extends('layouts.frame')

@section('title')

     Базовая Жизнь

@stop

@section('content')

     {{ Form::open(['url' => url("/directories/products/{$product_id}/edit/special-settings/mortgage/baserate-life/{$baserate_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


     <div class="form-group col-sm-6">
          <label class="col-sm-12 control-label">Тариф Муж. %</label>
          <div class="col-sm-12">
               {{ Form::text('tarife_man', titleFloatFormat($baserate->tarife_man, 0, 1, 3), ['class' => 'form-control sum']) }}
          </div>
     </div>

     <div class="form-group col-sm-6">
          <label class="col-sm-12 control-label">Тариф Жен. %</label>
          <div class="col-sm-12">
               {{ Form::text('tarife_woman', titleFloatFormat($baserate->tarife_woman, 0, 1, 3), ['class' => 'form-control sum']) }}
          </div>
     </div>


     <div class="form-group col-sm-6">
          <label class="col-sm-12 control-label">Возраст с (лет)</label>
          <div class="col-sm-12">
               {{ Form::text('age_from', titleNumberFormat($baserate->age_from, 1), ['class' => 'form-control sum']) }}
          </div>
     </div>

     <div class="form-group col-sm-6">
          <label class="col-sm-12 control-label">Возраст по (лет)</label>
          <div class="col-sm-12">
               {{ Form::text('age_to', titleNumberFormat($baserate->age_to, 1), ['class' => 'form-control sum']) }}
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

                  $.post('{{url("/directories/products/{$product_id}/edit/special-settings/mortgage/baserate-life/{$baserate_id}")}}', {
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

