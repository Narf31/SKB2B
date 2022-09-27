@extends('layouts.app')


@section('content')

    <div class="product_form">


        <div class="header_bab" >
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

                    <div title="Основная информация" data-view="orders.damages.partials.damage"></div>
                    <div title="Документы/Фото/Видео" data-view="orders.default.chat_doc"></div>
                    <div title="Платежи" data-view="orders.default.payments"></div>
                    <div title="История" data-view="orders.default.history"></div>

                </div>
            </div>
        </div>

        <div class="block-main" style="margin-top: -5px;">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" >
                    </div>

                </div>
            </div>
        </div>


    </div>



@stop




@section('js')

    {{ Html::script("https://js.pusher.com/5.0/pusher.min.js") }}


    <script>

        var channelChat;
        var TAB_INDEX = 0;

        $(function () {


            $('#tt').tabs({
                border:false,
                pill: false,
                plain: true,
                onSelect: function(title, index){
                    return selectTab(index);
                }
            });

            selectTab(0);


            var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: 'eu',
                forceTLS: true
            });

            channelChat = pusher.subscribe('chat-order-{{ $damage->id }}');

            channelChat.bind('new-message', function (data) {

                if(TAB_INDEX != 1){
                    return false;
                }

                addMessage(data);
                readMessage();
                readedMessages();

            });

            channelChat.bind('new-event-view', function (data) {

                if(TAB_INDEX != 1){
                    return false;
                }

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

        });

        function selectTab(id) {
            var tab = $('#tt').tabs('getSelected');
            load = tab.data('view');//$("#tab-"+id).data('view');
            TAB_INDEX = id;
            loaderShow();

            $.get("/orders/damages/{{$damage->id}}/get-html-block", {view:load}, function (response) {
                loaderHide();
                $("#main_container").html(response);
                initTab();

            })  .done(function() {
                loaderShow();
            })
                .fail(function() {
                    loaderHide();
                })
                .always(function() {
                    loaderHide();
                });

        }

        function initTab() {
            startMainFunctions();
        }




    </script>


@stop