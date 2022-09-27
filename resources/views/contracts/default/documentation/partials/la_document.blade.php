<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="tov-table">
            <thead>
            <tr>
                <th>Дата создания</th>
                <th>Файл</th>
                <th>Пользователь</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
                @foreach($contract->data->documents($key) as $document)
                    <tr>
                        <td>{{setDateTimeFormatRu($document->file->created_at)}}</td>
                        <td><a href="{{$document->file->url}}" target="_blank">{{$document->file->original_name}}</a></td>
                        <td>{{$document->file->user?$document->file->user->name:''}}</td>
                        <td>

                            @if(auth()->user()->hasPermission('matching', 'underwriting'))

                                {{Form::select("contract[liability_arbitration_manager][type_agr_id]", collect(\App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LADocuments::STATUS), $document->status_id, ['class' => 'form-control select2-ws', 'onchange' => "setStatusDocuments(this.value, {$document->id});"])}}

                            @else
                                {{\App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LADocuments::STATUS[$document->status_id]}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br/>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{Form::open(['url'=>"/contracts/online/{$contract->id}/action/product/documents/{$key}",'method' => 'post', 'class' => 'dropzone_ addManyDocForm']) }}
        <div class="dz-message" data-dz-message>
            <p>Перетащите сюда файлы</p>
            <p class="dz-link">или выберите с диска</p>
        </div>
        {{Form::close()}}
    </div>
</div>

@if(auth()->user()->hasPermission('matching', 'underwriting'))
<script>

    function setStatusDocuments(status_id, doc_id) {

        myGetAjax("/contracts/online/{{$contract->id}}/action/product/documents/"+doc_id+"/status/"+status_id);

    }


</script>

@endif