@php
    $titles = $category->hierarchy()->pluck('title')->toArray();

    uasort($doc, function($a, $b){
        return count($b) - count($a);
    });

@endphp


@extends('layouts.app')


@section('content')

    <div class="page-heading">
        <h2 class="inline-h1">Данная выгрузка относится к категории
            @php echo implode(" > ", $titles) @endphp
        </h2>
        <a href="{{ back()->getTargetUrl() }}" class="btn btn-primary btn-right">Назад</a>
    </div>
    <div class="form-horizontal" id="main_container" style="margin-top: 20px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-subheading">
                    <h2>Общая информация</h2>
                </div>
                <div class="block-view">
                    <div class="block-sub">
                        Для создания списка в шаблоне необходимо в строке разместить теги из раздела <sup style="font-size: 85%;">(список)</sup>,
                        а так же установить сам тег списка в любой свободной ячейке этой строки, как показано на изображении:
                        <div class="image">
                            <img src="/assets/img/excel_doc.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Поиск по тегам</label>
                        {{ Form::text('search_tag' , '', ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if(is_array($doc) && count($doc) > 0)
                @foreach($doc as $name_list => $list)
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="page-subheading">
                            <h2>{!! $name_list  !!}</h2>
                        </div>
                        <div class="block-view">
                            <div class="block-sub">
                                <div class="row">
                                    @foreach($list as $tag => $name)

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-search="{{ \Illuminate\Support\Str::lower($tag) . ' ' . \Illuminate\Support\Str::lower($name) }}">
                                            <div class="view-field">
                                                <span class="view-label">{!! $name !!}</span>
                                                <span class="view-value">
                                                    <span class="copy-tag">{!! '${'.$tag.'}' !!}</span> &nbsp;
                                                    <i class="fa fa-copy copy-doc-tag" title="Копировать тег в буфер"></i>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            @endif
        </div>

    </div>

@endsection


@section('js')

    <style>
        .copy-doc-tag{
            cursor: pointer;
        }
        .copy-doc-tag:hover{
            font-weight: bold;
        }
    </style>


    <script>
        $(function(){
            $(document).on('click', '.copy-doc-tag', function(){
                var text = $(this).siblings('.copy-tag').html();
                to_clipboard(text);
            });


            $(document).on('keyup', '[name="search_tag"]', function(){
                var query = $(this).val().toLowerCase();
                var query2 = switch_letters(query);

                if(query.length > 0){
                    loaderShow();
                    $('[data-search]').hide();
                    $('[data-search *='+query+'], [data-search *='+query2+']').show();
                }else{
                    $('[data-search]').show();
                }
                loaderHide();

            });
        })

    </script>

@endsection