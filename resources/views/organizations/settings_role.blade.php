<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Округ</h2>
        <a class="btn bg-primary pull-right fancybox fancybox.iframe" href="/supervision/organizations/{{$organization->id}}/settings_role/district/">
            Редактировать
        </a>

    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                @foreach($organization->settings_role_district as $district)

                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-12">
                            {{$district->district->title}}
                        </div>
                    </div>
                @endforeach


                @if(strlen($organization->supervision)>3)
                    <span class="btn btn-success" onclick="setViewRoleObj()">Обновить объекты</span>
                @endif

            </div>
        </div>
    </div>






</div>

<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Район</h2>
        <a class="btn bg-primary pull-right fancybox fancybox.iframe" href="/supervision/organizations/{{$organization->id}}/settings_role/area/">
            Редактировать
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                @php $district_id = 0 @endphp
                @foreach($organization->settings_role_district_area as $area)

                    @if($district_id != $area->district->id)
                        <h3>{{$area->district->title}}</h3>
                    @endif

                   @php $district_id = $area->district->id @endphp

                    <div class="form-group">
                        <div class="col-sm-12">
                            {{$area->area->title}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>




</div>



<script>



    function initTab() {

        startMainFunctions();

    }


    function setViewRoleObj() {

        loaderShow();

        $.get("/supervision/organizations/{{$organization->id}}/settings_role/refresh_obj", {}, function (response) {
            loaderHide();


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



</script>