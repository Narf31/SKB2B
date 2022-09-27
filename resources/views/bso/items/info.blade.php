<div class="product_form">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row form-horizontal" >
                <h2 class="inline-h1">Основная информация - {{$bso->type->title}}</h2>
                <br/><br/>

                <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">


                    <div class="view-field">
                        <span class="view-label">БСО номер</span>
                        <span class="view-value">
                            @if(auth()->user()->hasPermission('bso', 'edit_bso_title'))
                                <a href="{{url("/bso/items/{$bso->id}/edit_bso_title")}}" class="fancybox fancybox.iframe underline">{{$bso->bso_title}}</a>
                            @else
                                {{$bso->bso_title}}
                            @endif
                        </span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Филиал получатель</span>
                        <span class="view-value">
                            @if($bso->supplier)
                                @if(auth()->user()->hasPermission('bso', 'edit_supplier_org'))
                                    <a href="{{url("/bso/items/{$bso->id}/edit_supplier_org")}}" class="fancybox fancybox.iframe underline">{{$bso->supplier->title}}</a>
                                @else
                                    {{$bso->supplier->title}}
                                @endif
                            @endif
                        </span>
                    </div>


                    <div class="view-field">
                        <span class="view-label">Точка продаж</span>
                        <span class="view-value">{{($bso->point_sale)?$bso->point_sale->title:''}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">У кого сейчас</span>
                        <span class="view-value">{{($bso->user)?$bso->user->name:''}}</span>
                    </div>

                    @if($bso->contract_receipt && $bso->bso_class_id == 100)

                        <div class="view-field">
                            <span class="view-label">Договор</span>
                            <span class="view-value">
                                <a href="{{url("/bso/items/{$bso->contract_receipt->bso->id}/")}}" target="_blank">{{$bso->contract_receipt->bso->bso_title}}</a>
                            </span>
                        </div>

                    @endif


                    <div class="view-field">
                        <span class="view-label">Создан</span>
                        <span class="view-value">{{setDateTimeFormatRu($bso->time_create)}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Передан Агенту</span>
                        <span class="view-value">{{setDateTimeFormatRu($bso->transfer_to_agent_time)}}</span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Агент получивший полис</span>
                        <span class="view-value">
                            @if($bso->is_reserved == 1 && $bso->bso_cart_id > 0)
                                <a href="/bso/transfer/?bso_cart_id={{$bso->bso_cart_id}}" class="underline">Резерв: {{($bso->cars->user_to)?$bso->cars->user_to->name:''}}</a>
                            @else
                                {{($bso->agent)?$bso->agent->name.' - '.($bso->agent->organization?$bso->agent->organization->title:''):''}}
                            @endif
                        </span>
                    </div>
                    <div class="view-field">
                        <span class="view-label">Принят от Агента</span>
                        <span class="view-value">{{setDateTimeFormatRu($bso->transfer_to_org_time)}}</span>
                    </div>



                    <div class="view-field">
                        <span class="view-label">Событие / Статус
                            <a href="{{url("/bso/items/{$bso->id}/edit_bso_state")}}" class="fancybox fancybox.iframe underline"><i class="fa fa-edit"></i></a>

                        </span>
                        <span class="view-value">{{$bso->location->title}} /

                            @if($bso->file_id > 0)
                                <a href="{{ url($bso->scan->url) }}" target="_blank">{{$bso->state->title}}</a>
                            @else
                                {{$bso->state->title}}
                            @endif

                        </span>
                    </div>



                    <div class="clear"></div>
                </div>
            </div>
            <br/>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row form-horizontal" >
                <h2 class="inline-h1">Движение БСО</h2>
                <br/><br/>

                <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="form-horizontal">
                        <table class="tov-table">
                            <tr class="sort-row">
                                <th>Дата/время</th>
                                <th>Событие</th>
                                <th>Статус</th>
                                <th>Оператор</th>
                                <th>Получил</th>
                                <th>Акт</th>
                            </tr>
                            <tbody>

                            @if(sizeof($bso->logs))

                                @foreach($bso->logs as $bso_logs)

                                    <tr class="clickable-row">
                                        <td>{{setDateTimeFormatRu($bso_logs->log_time)}}</td>
                                        <td>{{$bso_logs->bso_location->title}}</td>
                                        <td>{{$bso_logs->bso_state->title}}</td>

                                        <td>{{$bso_logs->user ? $bso_logs->user->name : ''}}</td>
                                        <td>{{$bso_logs->user_to ? $bso_logs->user_to->name : ''}}</td>

                                        @if($bso_logs->act)
                                            <td>
                                                <a style="color:black !important;text-decoration:underline;" href="{{$bso_logs->act->get_link()}}">Акт № {{$bso_logs->act->act_number}}</a>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif

                                    </tr>

                                @endforeach
                            @endif


                            </tbody>
                        </table>
                    </div>

                    <div class="clear"></div>
                </div>
            </div>
            <br/>
        </div>
    </div>
</div>
