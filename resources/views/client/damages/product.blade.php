@extends('client.layouts.app')

@section('head')

@append

@section('content')




    <div class="row row__custom justify-content-between">


        <div class="row col-xs-12 col-sm-12 col-md-8 col-xl-8 col-lg-8 col__custom">

            @if(sizeof($product_info))

                @foreach($product_info as $info)

                    <div class="card__box info" id="info-{{$info->id}}" style="display: none;">
                        <div class="custom__title seo__item">
                            {{ $info->title }}
                        </div>
                        <div class="descr">
                            {!! $info->info_text !!}
                        </div>

                    </div>

                @endforeach

            @else

                <div class="page__title seo__item">
                    Описание отсутствует!
                </div>


            @endif



        </div>

        <div class=" col-xs-12 col-sm-12 col-md-4 col-xl-4 col-lg-4 col__custom">
            <div class="content__box-right" style="width: 100%;padding-bottom: 20px;">
                <div class="content__box-title seo__item">
                    {{ $product->title }}
                </div>
                <div class="calc__menu">
                    <ul>
                        @if(sizeof($product_info))
                            @foreach($product_info as $info)
                                <li>
                                    <a class="info-menu" style="cursor: pointer;" onclick="viewInfo({{$info->id}})">{{$info->title}}</a>
                                </li>
                            @endforeach
                        @endif
                        <li>
                            <a href="{{urlClient("/damages")}}" style="cursor: pointer;" >
                                <i class="fa fa-toggle-left"></i>
                                Назад
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


    </div>


    @if(sizeof($product_info))




    @endif

@endsection

@section('js')
<script>


    function viewInfo(id) {
        $('.info').hide();
        $('#info-'+id).show();
    }

    document.addEventListener("DOMContentLoaded", function (event) {

        @if(sizeof($product_info))

            $('.info-menu').first(':first').click();

        @endif
    });

</script>

@endsection