<div class="col-lg-12 driver driver-{{$i}}">
    {{ Form::hidden('contract[driver]['.$i.'][id]', $driver->id) }}
    <div class="row form-horizontal remove-border-top" style="border-top:1px dashed #c3c3c3;padding-top:5px;margin-top:5px;">
        <div class="col-lg-6">
            <h4 class="driver-title">Водитель {{$i}}</h4>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">Водитель (Страхователь)</span>
                @if($driver->same_as_insurer)
                    <span class="view-value">Да</span>
                @else
                    <span class="view-value">Нет</span>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">ФИО</span>
                <span class="view-value">{{$driver->fio}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">Дата рождения</span>
                <span class="view-value">{{setDateTimeFormatRu($driver->birth_date, 1)}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">Пол</span>
                @if($driver->sex)

                    <span class="view-value">жен.</span>
                @else
                    <span class="view-value">муж.</span>
                @endif
            </div>
        </div>


        <div class="clear"></div>
        <div class="col-lg-12">
            <h4>Документы</h4>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">В.У серия номер</span>
                <span class="view-value">{{$driver->doc_serie}} {{$driver->doc_num}}</span>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">Начало водительского стажа</span>
                <span class="view-value">{{setDateTimeFormatRu($driver->exp_date, 1)}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field">
                <span class="view-label">КБМ</span>
                <span class="view-value">{{$driver->kbm}}</span>
            </div>
        </div>

    </div>
</div>
