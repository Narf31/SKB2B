

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

   <input type="hidden" name="status" value="" id="status"/>

   <span class="btn btn-success pull-left" onclick="saveDamage('{{$damage->status_id}}')">
      Сохранить
   </span>

   @if(isset($STATYS[$damage->status_id+1]))
      <span class="btn btn-primary pull-right" onclick="saveDamage('{{$damage->status_id+1}}')">
         @if($damage->status_id+1 == 1)
            На распределение
         @elseif($damage->status_id+1 == 2)
            В работу
         @elseif($damage->status_id+1 == 3)
            На согласование
         @elseif($damage->status_id+1 == 4)
            На оплату
         @elseif($damage->status_id+1 == 5)
            В Архив
         @endif
      </span>
   @endif

</div>

<script>

   function saveDamage(status)
   {

       $("#status").val(status);

       loaderShow();

       $.post("{{url("/orders/damages/{$damage->id}/save")}}", $('#data-form').serialize(), function (response) {


           if (Boolean(response.state) === true) {

               flashMessage('success', "Данные успешно сохранены!");
               window.location = '{{url("/orders/damages/{$damage->id}")}}';

           }else {
               flashHeaderMessage(response.msg, 'danger');

           }

       }).always(function () {
           loaderHide();
       });

       return true;

   }

</script>

