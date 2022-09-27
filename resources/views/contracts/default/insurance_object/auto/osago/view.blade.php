<div class="row form-horizontal" >
    <h2 class="inline-h1">Транспортное средство</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Категория</span>
            <span class="view-value">{{ $object->category_auto ? $object->category_auto->title : "" }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Марка</span>
            <span class="view-value">{{ $object->mark ? $object->mark->title : "" }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Модель</span>
            <span class="view-value">{{ $object->model ? $object->model->title : "" }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Год выпуска</span>
            <span class="view-value">{{ $object->car_year }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Цель использования</span>
            <span class="view-value">{{ $object->purpose? $object->purpose->title : "" }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">VIN</span>
            <span class="view-value">{{ $object->vin }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Номер кузова</span>
            <span class="view-value">{{ $object->body_number }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Рег. номер</span>
            <span class="view-value">{{ $object->reg_number }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Мощность (л.с.)</span>
            <span class="view-value">{{ $object->power }}</span>
        </div>

        @if($object->passengers_count > 0)
        <div class="view-field">
            <span class="view-label">Кол-во мест</span>
            <span class="view-value">{{ $object->passengers_count }}</span>
        </div>
        @endif

        @if($object->weight > 0)
        <div class="view-field">
            <span class="view-label">Масса</span>
            <span class="view-value">{{ $object->weight }}</span>
        </div>
        @endif

        @if($object->capacity > 0)
        <div class="view-field">
            <span class="view-label">Грузоподъемность</span>
            <span class="view-value">{{ $object->capacity }}</span>
        </div>
        @endif

        <div class="view-field">
            <span class="view-label">Автомобиль используется с прицепом</span>
            <span class="view-value">{{ $object->is_trailer ? "Да" : "Нет"}}</span>
        </div>


        <div class="form-equally">
            <h4>Документы ТС</h4>
        </div>

        <div class="view-field">
            <span class="view-label">{{ \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::DOC_TYPE_TS[$object->doc_type] }}</span>
            <span class="view-value">{{ $object->docserie }} {{ $object->docnumber }}</span>
        </div>



        <div class="view-field">
            <span class="view-label">Дата выдачи</span>
            <span class="view-value">{{ getDateFormatRu($object->docdate) }}</span>
        </div>

        @if(strlen($object->dk_number) > 0)
        <div class="form-equally">
            <h4>Документ ДК</h4>
        </div>

        <div class="view-field">
            <span class="view-label"> Номер диагностической карты </span>
            <span class="view-value">{{ $object->dk_number }}</span>
        </div>


        <div class="view-field">
            <span class="view-label">Дата начала</span>
            <span class="view-value">{{ getDateFormatRu($object->dk_date_from) }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата окончания</span>
            <span class="view-value">{{ getDateFormatRu($object->dk_date_to) }}</span>
        </div>
        @endif

    </div>
</div>
