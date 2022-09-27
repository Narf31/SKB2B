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
            <span class="view-value">{{ $object->model ? $object->model->title : "" }} {{$object->model_classification_code}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Год выпуска</span>
            <span class="view-value">{{ $object->car_year }}</span>
        </div>


        <div class="view-field">
            <span class="view-label">VIN</span>
            <span class="view-value">{{ $object->vin }}</span>
        </div>


        <div class="view-field">
            <span class="view-label">Рег. номер</span>
            <span class="view-value">{{ $object->reg_number }}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Пробег</span>
            <span class="view-value">{{ titleFloatFormat($object->mileage, 0, 1) }}</span>
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


    </div>
</div>
