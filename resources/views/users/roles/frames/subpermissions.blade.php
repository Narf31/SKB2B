@extends('layouts.frame')
@section('title')
    Дополнительные права секции "{{ trans('users/roles.titles.' . $permission->title) }}"
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">
@endsection

@section('content')
    {{ Form::open(['url' => url("/users/roles/{$role->id}/permission/{$permission->id}/subpermissions"), 'method' => 'post', 'class' => 'form-horizontal']) }}
        <div class="form-horizontal">
            <table class="tov-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Вкладка</th>
                        <th>Редактирование</th>
                    </tr>
                </thead>
                <tbody>
                    @if(sizeof($permission->subpermissions))
                        @foreach($permission->subpermissions as $key => $subpermission)
                            @php
                                $sub = $subpermission->subpermission_roles()->where('role_id', $role->id)->first();
                            @endphp
                            <tr>
                                <td class="text-left">{{$key}}</td>
                                <td class="text-left">{{ trans('users/roles.subpermission_titles.' . $subpermission->title) }}</td>
                                <td class="text-left">
                                    {{ Form::checkbox("subpermission[{$subpermission->id}][view]", 1, $sub ? $sub->view : 0, ['class' => 'easyui-switchbutton', 'data-options'=>"onText:'Да',offText:'Нет'"]) }}
                                </td>
                                <td class="text-left">
                                    {{ Form::checkbox("subpermission[{$subpermission->id}][edit]", 1, $sub ? $sub->edit : 0, ['class' => 'easyui-switchbutton', 'data-options'=>"onText:'Да',offText:'Нет'"]) }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">{{ trans('form.empty') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    {{Form::close()}}
@endsection

@section('footer')
    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
@endsection

@section('js')
    <script>
        $(function () {

        });
    </script>
@append