<form id="product_form" class="product_form">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="page-heading">
            <h2 class="inline-h1">Согласования</h2>
        </div>

        <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row form-horizontal">

                <br/>




                <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="easyui-accordion">

                        @foreach(\App\Models\Directories\Products\Data\LiabilityArbitrationManager::FILE_CATEGORY as $key => $category)

                            <div title="{{$category}}">

                                @if($key == 0)
                                    <br/>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                                        @if($contract->calculation && $contract->calculation->matching && $contract->calculation->matching->status_id > 0)

                                            <div class="view-field">
                                                <span class="view-label">Андеррайтер</span>
                                                <span class="view-value">{{$contract->calculation->matching->check_user->name}}</span>
                                            </div>

                                            <div class="view-field">
                                                <span class="view-label">Дата время</span>
                                                <span class="view-value">{{setDateTimeFormatRu($contract->calculation->matching->updated_at)}}</span>
                                            </div>

                                            <div class="view-field">
                                                <span class="view-label">Статус</span>
                                                <span class="view-value">{{\App\Models\Contracts\Matching::STATYS[$contract->calculation->matching->status_id]}}</span>
                                            </div>


                                            <span style="font-size: 18px;color: red;">{{$contract->calculation->matching->comments}}</span>

                                        @endif
                                    </div>

                                    @foreach($contract->supplementary as $supplementary)
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <h3>Доп. соглашение {{$supplementary->title}}</h3>
                                        </div>
                                        <br/>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


                                            @if($supplementary->matching && $supplementary->matching->status_id > 0)

                                                <div class="view-field">
                                                    <span class="view-label">Андеррайтер</span>
                                                    <span class="view-value">{{$supplementary->matching->check_user->name}}</span>
                                                </div>

                                                <div class="view-field">
                                                    <span class="view-label">Дата время</span>
                                                    <span class="view-value">{{setDateTimeFormatRu($supplementary->matching->updated_at)}}</span>
                                                </div>

                                                <div class="view-field">
                                                    <span class="view-label">Статус</span>
                                                    <span class="view-value">{{\App\Models\Contracts\Matching::STATYS[$supplementary->matching->status_id]}}</span>
                                                </div>

                                                <span style="font-size: 18px;color: red;">{{$supplementary->matching->comments}}</span>

                                            @endif
                                        </div>
                                    @endforeach

                                @endif

                                <br/>

                                @if($key == 0)
                                    <div style="display: none;">

                                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                            @include("contracts.default.documentation.partials.la_document", ['key' => $key])

                                        </div>
                                    </div>
                                @else
                                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                        @include("contracts.default.documentation.partials.la_document", ['key' => $key])

                                    </div>
                                @endif
                            </div>

                        @endforeach

                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                    @include('contracts.chat.chat_forms', ['contract' => $contract, 'type' => 0])
                </div>


            </div>
        </div>
    </div>

</form>



<script>



    var channelChat;


    function initTab() {

        $('.easyui-accordion').accordion();

        $(".addManyDocForm").dropzone({
            paramName: 'file',
            maxFilesize: 1000,
            acceptedFiles: ".png, .jpg, .jpeg, .txt, .csv, .mp4, .mkv, .avi ,.mov,.avi,.mpeg4,.flv,.3gpp,image/*, .xls, .xlsx, .pdf, .doc, .docx",

            init: function () {
                this.on("queuecomplete", function () {
                    selectTab(TAB_INDEX);
                });
            }

        });





        channelChat = pusher.subscribe('chat-contract-{{ $contract->id }}');

        channelChat.bind('new-message', function (data) {

            addMessage(data);


        });

        channelChat.bind('pusher:subscription_succeeded', function(members) {

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


        var messagesContainer = $('.messages');
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);



        $("#addDocForm").dropzone({
            maxFilesize: 1000, // MB
            parallelUploads: 5,
            uploadMultiple: true,
            acceptedFiles: "image/*,.xlsx,.xls,.pdf,.doc,.docx",
            maxFiles: 10,
            init: function () {
                this.on("queuecomplete", function (file, message) {

                });

                this.on("success", function(file, message) {
                    if($("#type_chat").val() == 3){

                        var messageContent = renderMessageNotes(message);

                        var messagesContainer = $('.messages-notes');
                        messagesContainer.append(messageContent);
                        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
                        $('#message-notes').val('');
                    }
                });
            }
        });

        readMessage();


    }

    function saveTab() {

    }


</script>