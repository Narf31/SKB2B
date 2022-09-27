<div class="row form-horizontal">
    <h2 class="inline-h1">{{\App\Models\Contracts\Matching::TPYE[$matching->type_id]}}

    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">{{$matching->type_id == 0 ? 'Андеррайтер' : 'Сотрудник СБ'}}</span>
            <span class="view-value">{{($matching->check_user)?$matching->check_user->name:''}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата время</span>
            <span class="view-value">{{setDateTimeFormatRu($matching->updated_at)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Статус</span>
            <span class="view-value">{{\App\Models\Contracts\Matching::STATYS[$matching->status_id]}}</span>
        </div>


        <span style="font-size: 18px;color: red;">{{$matching->comments}}</span>
    </div>
</div>