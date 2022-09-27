


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row page-heading">
        <h2 class="inline-h1">Программы и тарифы</h2>
    </div>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="row table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Названия</th>
                </tr>
            </thead>
            <tbody>
            @foreach($json['programs'] as $id => $program)
                <tr onclick="openProgram({{$id}})" style="cursor: pointer;">
                    <td nowrap>{{$program['title']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clear"></div>
    </div>
</div>

<div class="clear"></div>


<script>

    _url = "{{$url}}";

    function openProgram(id) {
        openPage(_url+"?program="+id);
    }

</script>