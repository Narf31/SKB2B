
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="col-sm-12">
                    <h2>Входит в СРО
                        @if($state == 'edit')
                            <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/interactions-connections/0?type=1")}}')">Добавить</span>
                        @endif
                    </h2>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th width="40%" >Организация</th>
                            <th>Должность</th>
                            <th>Дата начало отношений</th>
                            <th>Дата завершения отношений</th>
                        </tr>
                        @foreach($general->interactions_connections_type(1) as $ic)
                            <tr @if($state == 'edit') style="cursor: pointer;" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/interactions-connections/{$ic->id}?type=1")}}')" @endif>
                                <td>{{$ic->general_organization->title}}</td>
                                <td>{{$ic->job_position}}</td>
                                <td>{{setDateTimeFormatRu($ic->date_from, 1)}}</td>
                                <td>{{setDateTimeFormatRu($ic->date_to, 1)}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @if($general->type_id == 0)
                    @include("general.subjects.info.fl.interactions_connections.{$state}")
                @else
                    @include("general.subjects.info.ul.interactions_connections.{$state}")
                @endif

            </div>
        </div>
    </div>
</div>




<script>

    function startMainFunctions()
    {



    }




</script>