
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="col-sm-12">
                    <h2>Дополнительные документы
                        @if($state == 'edit')
                        <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/document/0")}}')">Добавить</span>
                        @endif
                    </h2>
                </div>

                @php

                    if($general->type_id == 0){
                        $DOC_TYPE = collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn'));
                    }else{
                        $DOC_TYPE = \App\Models\Contracts\Subjects::DOC_TYPE_UL;
                    }
                @endphp

                <table class="tov-table">
                    <thead>
                    <tr>
                        <th>Тип документа</th>
                        <th>Серия / Номер</th>
                        <th>Дата выдачи</th>
                        <th>Актуально</th>
                        <th>Основной</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($general->documents as $document)

                            <tr @if($state == 'edit') style="cursor: pointer;" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/document/{$document->id}")}}')" @endif>
                                <td>{{$DOC_TYPE[$document->type_id]}}</td>
                                <td>{{$document->serie}} {{$document->number}}</td>
                                <td>{{setDateTimeFormatRu($document->date_issue, 1)}}</td>
                                <td>{{($document->is_actual == 1)?"Да":"Нет"}}</td>
                                <td>{{($document->is_main == 1)?"Да":"Нет"}}</td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>



            </div>
        </div>
    </div>
</div>




<script>

    function startMainFunctions()
    {



    }




</script>