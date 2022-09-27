@extends('layouts.app')

@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Результат поиска</h2>

        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">



                        @if(sizeof($result))


                            @if($is_bso == 1)
                                @include("search.bsos", ['bsos'=>$result])
                            @else
                                @include("search.contracts", ['contracts'=>$result])
                            @endif

                        @elseif(!sizeof($result) && !sizeof($generals))

                            {{$errors_msg}}

                        @endif

                        @if(sizeof($generals))
                                @include("search.generals", ['generals'=>$generals])

                        @endif



                    </div>
                </div>
            </div>
        </div>


    </div>
@stop

@section('js')



@stop