<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    @if($view == 'edit')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {!! Form::open(['url'=>$url_scan,'method' => 'post', 'class' => '', 'id' => 'comment_pso']) !!}

        <div class="form-group">
            <div class="col-sm-12">
                {{ Form::textarea('comment_pso', isset($order->comment_pso) ? $order->comment_pso : '', ['class' => 'form-control','placeholder' => 'Поле для комментария']) }}
            </div>
        </div>
        <span class="btn btn-success btn-right" onclick="setCommentPso()">Сохранить комментарий</span>

        {!! Form::close() !!}
    </div>
    @else
    {{isset($order->comment_pso) ? $order->comment_pso : ''}}
    @endif
</div>

<script>



function setCommentPso()
{
    @if($view == 'edit')

    loaderShow();


    $.post("{{$url_scan}}", {'comment_pso': $('[name="comment_pso"]').val()}, function (response) {


        if (Boolean(response.state) === true) {

            flashMessage('success', "Данные успешно сохранены!");


        }else {
            flashHeaderMessage(response.msg, 'danger');

        }

    }).always(function () {
        loaderHide();
    });

    @endif
}





</script>