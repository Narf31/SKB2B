@extends('layouts.app')

@section('head')

    <style>
        tr.green{
            background-color: #ffd6cc;
        }
    </style>

@append

@section('content')


    <div class="page-heading">
        <h2>Акт # {{$act->act_number}} от {{setDateTimeFormatRu($act->time_create)}}



        </h2>
    </div>
    <div class="divider"></div>

    <br/>
    <div class="row">

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            <div class="form-horizontal">
                <div class="form-group">

                    <div class="col-sm-4">
                        <label class="col-sm-12 control-label">Тип акта</label>
                        <div class="col-sm-12">
                            {{$act->act_name}}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="col-sm-12 control-label">Создал</label>
                        <div class="col-sm-12">
                            {{($act->bso_manager)?$act->bso_manager->name:''}}
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <a class="btn btn-success pull-right doc_export_btn" href="/bso_acts/acts_implemented/details/{{$act->id}}/export">Выгрузить в XLS</a>
                    </div>

                </div>
            </div>


        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            @if($act->realized_state_id == 0)
                <span class="btn btn-danger pull-left" onclick="deleteAct()">Удалить акт</span>
                @if(auth()->user()->hasPermission('bso_acts', 'acts_implemented_accept'))
                    <span class="btn btn-success pull-right" onclick="acceptAct()">Подтвердить</span>
                @endif
                <hr/>
                @include('bso_acts.acts_transfer_tp.info.details.edit', ['act'=>$act])
            @endif


        </div>
    </div>

    <div class="divider"></div>

    @include('bso_acts.acts_transfer_tp.info.contract.list', ['act'=>$act, 'bsos'=>$act->bso_items])



@stop

@section('js')

    <script>


        $(function () {







        });


        function show_checked_options() {
            show_actions();
        }

        function check_all_bso(obj) {
            $('.bso_item_checkbox').attr('checked', $(obj).is(':checked'));
            show_actions();
        }

        function show_actions() {
            if ($('.bso_item_checkbox:checked').length > 0) {
                $('.event_form').show();
            } else {
                $('.event_form').hide();
            }

            $('.event_td').addClass('hidden');
            $('.event_' + $('#event_id').val()).removeClass('hidden');
            highlightSelected();

        }

        function highlightSelected() {
            $('.bso_item_checkbox').each(function(){
                $(this).closest('tr').toggleClass('green', $(this).is(':checked'));
            });
        }


        function add_spoiled(bso_id) {
            openFancyBoxFrame('{{url("/bso_acts/acts_transfer_tp/spoiled/edit/")}}?agent_id=0&bso_id='+bso_id);
        }


        @if($act->realized_state_id == 0)

        function deleteAct() {

            loaderShow();

            $.post("{{url("/bso_acts/acts_transfer_tp/details/{$act->id}/delete_act")}}", {}, function (response) {
                loaderHide();
                window.location = '{{url("/bso_acts/acts_transfer_tp/")}}';

            }).done(function() {
                loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });

        }

        function deleteItemsAct() {

            var item_array = [];
            $('.bso_item_checkbox:checked').each(function () {
                item_array.push($(this).val());
            });


            loaderShow();

            $.post("{{url("/bso_acts/acts_transfer_tp/details/{$act->id}/delete_items")}}", {item_array: JSON.stringify(item_array)}, function (response) {
                loaderHide();

                reload();

            }).done(function() {
                loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });


        }

        @if(auth()->user()->hasPermission('bso_acts', 'acts_implemented_accept'))

        function acceptAct() {
            loaderShow();

            $.post("{{url("/bso_acts/acts_transfer_tp/details/{$act->id}/accept")}}", {}, function (response) {
                loaderHide();
                reload();

            }).done(function() {
                loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });
        }

        @endif

        @endif

    </script>


@stop