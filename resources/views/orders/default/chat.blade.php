<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="block-inner" style="border: 1px solid #ccc; padding: 5px">



        <div class="messages" style="height: 500px; overflow-y: scroll">

            @foreach($order->chats as $msg)

                <div class="panel{{($msg->sender_id != auth()->id()) ? " panel-default player pull-left" : " panel-success employee pull-right"}}">
                    <div class="panel-heading">
                        <strong>{{ $msg->sender->name }}</strong>
                        <span class="pull-right{{($msg->is_player == \App\Models\Orders\OrdersChat::PLAYER) ? "" : " text-mutted"}}">{{ $msg->status == \App\Models\Orders\OrdersChat::STATUS_SENT ? $msg->date_sent->format('d.m.Y H:i'): $msg->date_receipt->format('d.m.Y H:i') }}
                              <span class="status">({{ $msg->status_title }})</span></span>
                    </div>
                    <div class="panel-body">
                        <div>
                            {{$msg->text}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

            @endforeach
        </div>



        <div class="input-group">
            <input id="message-text" type="text" class="form-control" placeholder="Текст сообщения"/>
            <span class="input-group-btn">
                <button class="btn btn-primary send-btn" type="button"  data-loading-text="Отправка..." onclick="setMsg()">Отправить</button>
            </span>
        </div>
    </div>

    <style>
        .messages .panel {
            border: #ccc 1px solid;
            -moz-border-radius: 40px;
            -webkit-border-radius: 40px;
            /*border-radius: 0 0 0 0;*/
            border-radius: 40px;
            padding: 8px 15px;
            margin-top: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }
        .messages .panel-body {
            padding: 5px;
            border-color: #fff;

        }
        .messages .panel{
            -webkit-box-shadow: 0 0 0 0;
            box-shadow: 0 0 0 0;
        }
        .messages .panel-default > .panel-heading{
            color: unset;
            background-color: unset;
            border-color: unset;
        }
        .messages .panel-heading{
            border-bottom: unset;
        }
        .messages .panel-success > .panel-heading{
            color: unset;
            background-color: unset;
            border-color: unset;
        }
        .messages .panel-success > .panel-body{
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #dff0d8;
        }
        .messages .panel-success{
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .messages .panel-default> .panel-heading{
            color: unset;
            background-color: unset;
            border-color: unset;
        }
        .messages .panel-default> .panel-body{
            color: #404040;
            background-color: #f5f5f5;
            border-color: #f5f5f5;
        }
        .messages .panel-default{
            color: #333333;
            background-color: #f5f5f5;
            border-color: #f5f5f5;
        }
        .messages .panel-heading span{
            margin-left: 10px;
        }
        .messages .panel-footer {
            border: 1px solid #fff;
        }
    </style>



    <script>

        $('#message-text').on('keypress', function (e) {
            if (e.which === 13) {
                setMsg();
            }
        });



        function setMsg() {
            var text = $('#message-text').val();
            if (text.length > 0) {
                $.post('{{ "/orders/actions/chat/$order->id/push" }}', {text: text});
            }
        }

        function renderMessage(message) {
            var style = (message.sender_id != parseInt('{{auth()->id()}}')) ? " panel-default player pull-left" : " panel-success employee pull-right";
            var mutted = (message.is_player === 1) ? "" : " text-muted";
            var status = (message.status === 1) ? "(Прочитано)" : "(Не прочитано)";
            return '<div class="panel'+style+'">' +
                '<div class="panel-heading">' +
                '<strong>' + message.sender + '</strong>' +
                '<span class="pull-right'+mutted+'">' + message.date +
                '&nbsp;&nbsp;<span class="status">' + status + '</span></span>' +
                '</div>' +
                '<div class="panel-body">' +
                '<div>' + message.text + '</div>' +
                '</div>' +
                '</div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>';
        }

        function addMessage(message) {
            $('#message-text').val('');
            var messageContent = renderMessage(message);
            var messagesContainer = $('.messages');
            messagesContainer.append(messageContent);
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
        }

        function readMessage() {
            $.get('{{ "/orders/actions/chat/$order->id/read" }}', function () {
                $(".player .panel-heading span.status").text('(Прочитано)');
            });
        }

        function readedMessages() {
            $(".employee .panel-heading span.status").text('(Прочитано)');
        }

        function addFileContract(id) {

            $("#document-box").append(myGetAjax('{{ "/orders/actions/chat/$order->id/documents" }}?id='+id));
            activeDocuments();
        }





    </script>

</div>