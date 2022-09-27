@extends('layouts.frame')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-body">

                        <div class="col-md-12">

                            {{ Form::model($schedule, ['url' => url("/users/users/$user->id/schedule/{$date->format('Y-m-d')}"), 'method' => 'post', 'class' => 'schedule-form']) }}

                            <table class="table">

                                <tr>
                                    <td colspan="2" style="height: 15px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-6">{{ trans('salaries/schedule.date') }}</th>
                                    <td class="col-md-6">{{ $date->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="height: 15px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-6">
                                        {{ trans('salaries/schedule.time') }}
                                    </th>
                                    <td class="col-md-6">
                                        <table class="table" style="border: 0">
                                            <tr>
                                                <td>с</td>
                                                <td>{{ Form::text('datetime_from', $schedule->datetime_from ? $schedule->datetime_from->format('H:i') : '', ['class' => 'form-control time']) }}</td>
                                                <td>по</td>
                                                <td>{{ Form::text('datetime_to', $schedule->datetime_to ? $schedule->datetime_to->format('H:i') : '', ['class' => 'form-control time']) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="height: 15px;"></td>
                                </tr>
                                <tr>
                                    <th class="col-md-6">{{ trans('salaries/schedule.state') }}</th>
                                    <td class="col-md-6">{{ Form::select('state_id', $states, old('state_id'), ['class' => 'form-control', 'required']) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="height: 15px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-theme-dark pull-right">{{ trans('form.buttons.save') }}</button>
                                    </td>
                                </tr>

                            </table>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('.schedule-form').submit(function (e) {

                e.preventDefault();

                $.post($(this).prop('action'), $(this).serialize() + '&_method=PATCH', function (response) {
                    if(response.success){
                        window.parent.updateUserScheduleStateTitle(response.msg);
                    }
                });

            });
        });
    </script>
@endsection