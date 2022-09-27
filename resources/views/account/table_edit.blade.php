@extends('layouts.frame')

@section('head')


@endsection


@section('title')
    Настройка таблицы
@endsection


@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0; padding-right: 0;">
            <h4 style="margin-left: 25%;">Доступные колонки</h4>
            <ul id="sortable_table_columns" class="connectedSortable">
                @foreach($table_columns as $column)

                    <li>
                        <input type="hidden" name="{{$column['id']}}" value="1">
                        {{$column['column_name']}}
                    </li>

                @endforeach
            </ul>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0; padding-right: 0;">
            <h4 style="margin-right: 25%;">Показывать</h4>
            <form id="user_columns" data-table="{{ $table_key }}">
                <ul id="sortable_user_columns" class="connectedSortable">
                    @foreach($user_columns as $column)
                        <li>
                            <input type="hidden" name="{{$column['id']}}" value="1">
                            {{$column['column_name']}}
                        </li>
                    @endforeach
                </ul>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <span onclick="saveTableSettingsForm()" id="save_button_table_columns" style="margin: auto;"
          class="btn btn-primary btn-inline">{{ trans('form.buttons.save') }}</span>
@endsection

@section('js')
    <script>

        var one_column_height = null;

        $(function () {

            recount();

            $("#sortable_table_columns, #sortable_user_columns").sortable({
                connectWith: ".connectedSortable",
                change: function (event, ui) {
                    recount();
                },
                update: function(){
                    reheight();
                },
                sort: function(){
                    recount();
                }
            });

            height = ($('.ui-sortable-handle').css('height'));
            padding_t = ($('.ui-sortable-handle').css('paddingTop'));
            padding_b = ($('.ui-sortable-handle').css('paddingBottom'));


            one_column_height = parseInt(height.replace('px', '')) + parseInt(padding_t.replace('px', '')) + parseInt(padding_b.replace('px', ''));

        });

        function recount() {
            l_h = $('#sortable_table_columns').css('height');
            r_h = $('#sortable_user_columns').css('height');

            l_h = parseInt(l_h.replace('px', ''));
            r_h = parseInt(r_h.replace('px', ''));

            if (l_h > r_h) {
                $('#sortable_user_columns').css('height', l_h + 'px');
            } else {
                $('#sortable_table_columns').css('height', r_h + 'px');
            }

        }

        function reheight(){
            children_suc = document.getElementById('sortable_user_columns').children.length;
            children_stc = document.getElementById('sortable_table_columns').children.length;

            wrapper = parseInt($('#sortable_table_columns').css('padding-top').replace('px', ''));


            total_suc = one_column_height * children_suc + wrapper - 7 * children_suc;
            total_stc = one_column_height * children_stc + wrapper - 7 * children_stc;

            $('#sortable_user_columns').css('height', total_suc+'px');
            $('#sortable_table_columns').css('height', total_stc+'px');


        }

        function saveTableSettingsForm() {

            if ($('#save_button_table_columns').attr('disabled') == undefined){
                $('#save_button_table_columns').attr('disabled','disabled');
                var data = $('#user_columns').serialize();
                $.post("{{url("/account/table_setting/$table_key/save/")}}", data, function (res) {
                    if (res.status === 'ok') {
                        window.parent.location.reload();
                    }
                });
            }

            return false;
        }

    </script>
@endsection