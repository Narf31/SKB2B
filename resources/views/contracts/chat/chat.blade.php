@include('contracts.chat.chat_forms', ['contract' => $contract])



@section('pusher')

    <script>

        var channelChat = pusher.subscribe('chat-contract-{{ $contract->id }}');

        channelChat.bind('new-message', function (data) {
            addMessage(data);
            readMessage();
            readedMessages();
        });

        channelChat.bind('new-event-view', function (data) {

            if(data.event == 'reload')
            {
                reload();
            }

            if(data.event == 'add-file')
            {
                addFileContract(data.id);
            }

            if(data.event == 'delete-file')
            {

                $("#document-"+data.id).remove();
                activeDocuments();
            }


        });

    </script>

@append