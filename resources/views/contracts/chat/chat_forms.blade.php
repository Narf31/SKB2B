

        <div class="block-inner" style="border: 1px solid #ccc; padding: 5px">

            <div class="messages" style="height: 500px; overflow-y: scroll">

                @foreach($contract->chatsMsg($type) as $msg)

                    <div class="panel{{($msg->sender_id != auth()->id()) ? " panel-default player pull-left" : " panel-success employee pull-right"}}">
                        <div class="panel-heading">
                            <strong>{{ $msg->sender->name }}</strong>

                        </div>
                        <div class="panel-body">
                            @if($msg->is_file == 1)
                                @php
                                    $file = (object)$msg->getFileArray();
                                @endphp
                                <a target="_blank"  href="{{ $file->url }}">
                                    <img style="max-height: 150px;margin: 0 auto;" src="{{$file->view_url}}"
                                         onerror="this.onerror=null;this.src='/images/extensions/unknown.png';">
                                </a>
                            @else
                                <div>
                                    {{$msg->text}}
                                </div>
                            @endif
                        </div>
                        <span class="pull-right{{($msg->is_player == \App\Models\Contracts\ContractsChat::PLAYER) ? "" : " text-mutted"}}">

                            @if($msg->receipt)

                                <span class="hint--bottom" style="cursor: pointer;" aria-label="{{$msg->receipt->name}} - {{$msg->date_receipt->format('d.m.Y H:i')}}">
                                            <i class="fa fa-check info-hint" aria-hidden="true"></i>
                                        </span>

                            @endif

                            {{ $msg->status == \App\Models\Contracts\ContractsChat::STATUS_SENT ? $msg->date_sent->format('d.m.Y H:i'): $msg->date_receipt->format('d.m.Y H:i') }}
                            </span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                @endforeach
            </div>



            <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" onclick="viewLoadFiles('{{$type}}')">
                        <i class="fa fa-upload"></i>
                    </button>
                </span>

                <input id="message-text" type="text" class="form-control" placeholder="Текст сообщения"/>
                <span class="input-group-btn">
                    <span class="btn btn-primary" onclick="setMsg()">
                       <i class="fa fa-share"></i>
                    </span>
                </span>
            </div>

        </div>


        <div class="hidden">
            {!! Form::open(['url'=>"/contracts/actions/chat/$contract->id/documents/{$type}/load",'method' => 'post', 'class' => 'dropzone_', "id"=>"addDocForm"]) !!}
            <input type="hidden" name="type_chat" id="type_chat" value="{{$type}}"/>
            <input type="file" name="file" id="clickFile" multiple/>
            {!! Form::close() !!}
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
            min-width: 280px;
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
                $.post('{{ "/contracts/actions/chat/$contract->id/push/{$type}/" }}', {text: text});
            }
        }

        function renderMessage(message) {
            var style = (message.sender_id != parseInt('{{auth()->id()}}')) ? " panel-default player pull-left" : " panel-success employee pull-right";
            var mutted = (message.is_player === 1) ? "" : " text-muted";

            var myHtml = '';

            myHtml = '<div class="panel'+style+'">' +
                '<div class="panel-heading">' +
                '<strong>' + message.sender + '</strong>' +

                '&nbsp;&nbsp;</span>' +
                '</div>' +
                '<div class="panel-body">';

            if(message.is_file == 1){

                file = message.file;

                myHtml = myHtml +
                    '<a target="_blank"  href="'+ file.url+'">' +
                    '<img style="max-height: 150px;margin: 0 auto;" src="'+ file.view_url +'">' +
                    '</a>';

            }else{
                myHtml = myHtml +
                    '<div>' + message.text + '</div>';
            }



            myHtml = myHtml +'</div>' +
                '<span class="pull-right'+mutted+'">' + message.date +
                '</div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>';

            return myHtml;
        }
        
        function addMessage(message) {

            if($('*').is('.messages')){
                $('#message-text').val('');
                var messageContent = renderMessage(message);
                var messagesContainer = $('.messages');
                messagesContainer.append(messageContent);
                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
            }


        }

        function readMessage() {
            $.get('{{ "/contracts/actions/chat/$contract->id/read/{$type}" }}', function () {
                //$(".player .panel-heading span.status").text('(Прочитано)');
            });
        }

        function readedMessages() {
            //$(".employee .panel-heading span.status").text('(Прочитано)');
        }

        function addFileContract(id) {

            $("#document-box").append(myGetAjax('{{ "/contracts/actions/chat/$contract->id/documents/{$type}" }}?id='+id));
            activeDocuments();
        }


        function viewLoadFiles(type_chat)
        {

            $("#type_chat").val(type_chat);
            $("#addDocForm").click();
        }


        $('a[data-toggle="tab"].chat_tab').on('shown.bs.tab', function () {
            if($('*').is('.messages')) {
                var messagesContainer = $('.messages');
                messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
                readMessage();
                count_unread();
            }
        });
    </script>


