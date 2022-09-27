<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h3>Санкционные списки
        <span class="btn btn-info pull-right" onclick="updateSubscriptionLists()"><i class="fa fa-history"></i></span>
    </h3>

    <table class="tov-table">
        <thead>
        <tr>
            <th>Список</th>
            <th>Дата проверки</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @if(isset($lists))

        @if(isset($lists->terroristIndividualEntries))
            <tr>
                <td>Террорист</td>
                <td>{{setDateTimeFormatRu($lists->listsStatistic->terroristListActualDate, 1)}}</td>
                <td>{{(count($lists->terroristIndividualEntries)>0?"Да":"Нет")}}</td>
            </tr>
        @endif

        @if(isset($lists->sanctionsEntries))
            <tr>
                <td>Санкционный</td>
                <td>{{setDateTimeFormatRu($lists->listsStatistic->secoListActualDate, 1)}}</td>
                <td>{{(count($lists->sanctionsEntries)>0?"Да":"Нет")}}</td>
            </tr>
        @endif

        @if(isset($lists->sanctionsToUkraineIndividuals))
            <tr>
                <td>Санкционный украинский</td>
                <td>{{setDateTimeFormatRu($lists->listsStatistic->sanctionsToUkraineListActualDate, 1)}}</td>
                <td>{{(count($lists->sanctionsToUkraineIndividuals)>0?"Да":"Нет")}}</td>
            </tr>
        @endif


        @if(isset($lists->pepEntries) && isset($lists->pepEntries[0]))

            <tr>
                <td>ПДЛ</td>
                <td>{{setDateTimeFormatRu($lists->pepEntries[(count($lists->pepEntries)-1)]->documentDate, 1)}}</td>
                <td>{{$lists->pepEntries[(count($lists->pepEntries)-1)]->position}}</td>
            </tr>

        @else
            <tr>
                <td>ПДЛ</td>
                <td></td>
                <td>Нет</td>
            </tr>

        @endif
        @endif


        </tbody>
    </table>

</div>

<script>

    function updateSubscriptionLists()
    {

        loaderShow();

        $.post('{{url("/general/subjects/edit/{$general->id}/podft-check")}}', $('#form-data').serialize(), function (response) {


            if (Boolean(response.state) === true) {

                selectTab(TAB_INDEX);

            }else {
                if(response.errors){
                    $.each(response.errors, function (index, value) {
                        flashHeaderMessage(value, 'danger');
                        $('[name="' + index + '"]').addClass('form-error');
                    });
                }else{
                    flashHeaderMessage(response.msg, 'danger');
                }

            }

        }).always(function () {
            loaderHide();
        });

    }

</script>