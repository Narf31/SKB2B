@extends('layouts.app')

@section('content')

        <div class="page-heading">
            <h1 class="inline-h1">{{ trans('menu.products') }}</h1>
            <a class="btn btn-primary btn-right" href="{{ url('/directories/products/create')  }}">
                {{ trans('form.buttons.create') }}
            </a>
        </div>

        <div class="block-main">
            <div class="block-sub">
                @if(sizeof($products))
                    <table class="tov-table">
                        <thead>
                            <tr>
                                <th><a href="javascript:void(0);">{{ trans('settings/banks.title') }}</a></th>
                                <th><a href="javascript:void(0);">{{ trans('settings/banks.is_actual') }}</a></th>
                                <th><a href="javascript:void(0);">Тип фин. политики</a></th>
                                <th><a href="javascript:void(0);">Категория</a></th>
                                <th><a href="javascript:void(0);">Доступно оформление онлайн</a></th>

                            </tr>
                        </thead>

                        @foreach($products as $product)
                            <tr onclick="openPage('{{url ("/directories/products/$product->id/edit")}}')" style="cursor: pointer;">
                                <td>{{ $product->title }}</td>
                                <td>{{ ($product->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                                <td>{{ \App\Models\Directories\Products::FIN_TYPE[$product->financial_policy_type_id] }}</td>
                                <td>{{ $product->category->title }}</td>
                                <td>{{$product->is_online ? 'Да' : 'Нет'}}</td>
                            </tr>
                        @endforeach

                    </table>
                @else
                    {{ trans('form.empty') }}
                @endif
            </div>
        </div>


@endsection

