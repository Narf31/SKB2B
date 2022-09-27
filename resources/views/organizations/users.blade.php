<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-inner">
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="control-label">Подразделение</label>
                            {{ Form::select('department_id', \App\Models\Settings\Department::where('org_type_id', $organization->org_type_id)->pluck('title', 'id')->prepend('Все', 0), 0, ['class' => 'form-control select2-all', 'onchange' => 'loadItems()']) }}
                        </div>


                        @if(auth()->user()->hasPermission('directories', 'organizations_user'))
                            <div class="col-sm-9">
                    <span onclick="openFancyBoxFrame('/users/frame/?user_id=0&org_id={{$organization->id}}')" class="btn btn-primary pull-right">
                        {{ trans('form.buttons.add') }}
                    </span>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
        <div id="table"></div>
    </div>
</div>


    <script>

        $(function () {
            loadItems();
        });


        function getData(){
            return {
                department_id: $('[name="department_id"]').val()
            }
        }

        function loadItems() {
            loaderShow();

            var data = getData();

            $.post('/directories/organizations/organizations/{{$organization->id}}/get_users_table', data, function (res) {

                $('#table').html(res.html);
                loaderHide();

                $('.tov-table').DataTable({
                    autoWidth: true,
                    searching: false,
                    info: false,
                    paging: false,

                });

            });

        }
    </script>


