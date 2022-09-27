<div class="row form-horizontal" >
    <h2 class="inline-h1">Территория страхования</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Адрес</span>
            <span class="view-value">{{$object->address}}</span>
        </div>
        <div>
            <span>
                Дом {{$object->address_house}}
                Строение {{$object->address_block}}
                Квартира {{$object->address_flat}}
                Этаж {{$object->flat_floor}}
                Дом этажей {{$object->house_floor}}
            </span>
        </div>
        <div>
            <span>
                {{$object->comments}}
            </span>
        </div>
        <br/>
    </div>
</div>

