@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('settings/financial_policy.index.page_title') }}</div>

                    <div class="panel-body">
                        <div class="col-md-12">
                            <a class="btn btn-theme-dark pull-right" href="{{ url('/settings/financial_policy/create')  }}">{{ trans('form.buttons.create') }}</a>
                        </div>
                        <div class="col-md-12">
                            @if(sizeof($financialPolicies))
                                <table class="table table-bordered">
                                    <tr>
                                        <th>{{ trans('settings/financial_policy.index.id') }}</th>
                                        <th>{{ trans('settings/financial_policy.index.title') }}</th>
                                        <th>{{ trans('settings/financial_policy.index.is_active') }}</th>
                                        <th>{{ trans('settings/financial_policy.index.types_trailers_title') }}</th>
                                        <th>{{ trans('settings/financial_policy.index.kv_km') }}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                    @foreach($financialPolicies as $financialPolicy)
                                        <tr>
                                            <td>{{ $financialPolicy->id  }}</td>
                                            <td>{{ $financialPolicy->title }}</td>
                                            <td>{{ ($financialPolicy->is_active==1)?trans('settings/financial_policy.index.is_active_yes'):trans('settings/financial_policy.index.is_active_no') }}</td>
                                            <td>{{ $financialPolicy->types_trailers_title }}</td>
                                            <td>{{ $financialPolicy->kv_km }}</td>
                                            <td class="text-center"><a href="{{url ("/settings/financial_policy/$financialPolicy->id/edit")}}" class="btn btn-theme-dark pull-right">{{ trans('form.buttons.edit') }}</a></td>
                                            <td class="text-center">
                                                <button class="btn btn-theme-dark pull-right" onclick="deleteItem('{{ $financialPolicy->id  }}')">{{ trans('form.buttons.delete') }}</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            @else
                                {{ trans('settings/financial_policy.index.list_not_financial_policy') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        function deleteItem(id) {
            if (!confirm('Are you sure?')) return false;

            $.post('{{ url('/settings/financial_policy') }}/' + id, {
                _method: 'delete'
            }, function () {
                window.location.reload();
            });
        }
    </script>

@stop
