<div class="content__box" style="width: 100%;padding-bottom: 20px;">
    <div class="content__box-title seo__item">
        Документы
    </div>
    <div class="calc__menu">
        <ul>

            @foreach($masks as $mask)

                <li><a href="{{$mask->getUrlAttribute()}}">{{$mask->original_name}}</a></li>

            @endforeach


        </ul>
    </div>
</div>

<div class="clear"></div>



