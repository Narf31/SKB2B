<input type="hidden" value="" id="status_work"/>
<input type="hidden" value="" id="work_comments"/>


@if(($damage->position_type_id == 0 && $damage->work_user_id == auth()->id()) ||
($damage->position_type_id == 1 && $damage->point_sale_id == auth()->user()->point_sale_id))

    @if($damage->work_status_id == 0)

        @if($damage->position_type_id == 0)
        <span class="btn btn-danger pull-left" onclick="commitsWork()">
            Отказаться
        </span>
        @endif

        <span class="btn btn-success pull-right" onclick="saveWork(2, '')">
          Взять в работу
        </span>

    @elseif($damage->work_status_id == 2)
        <span class="btn btn-success pull-right" onclick="saveWork(3, '')">
          Приехал
        </span>
    @elseif($damage->work_status_id == 3)
        <span class="btn btn-success pull-right" onclick="saveWork(4, '')">
          Закончил
        </span>
    @endif




@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>

   function saveWork(status, comments)
   {


       loaderShow();

       $.post("{{url("/orders/damages/{$damage->id}/work-status")}}", {work_status:status, work_comments:comments}, function (response) {


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

   function commitsWork(){
       Swal.fire({
               title: 'Укажите причину отказа',
               input: 'text',
               inputAttributes: {
                   autocapitalize: 'off'
               },
               showCancelButton: true,
               confirmButtonText: 'Отказаться',
                cancelButtonText: 'Отмена',
               showLoaderOnConfirm: true,

       }).then((result) => {
           if (result.value)
           {
               saveWork(1, result.value);
           }
        });
   }

</script>

