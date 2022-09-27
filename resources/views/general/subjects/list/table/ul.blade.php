@if(sizeof($generals))
    <table class="tov-table">
        <thead>
        <tr>
            <th>Названия</th>
            <th>ИНН</th>
            <th>Ответственный</th>
            <th>Статус</th>
        </tr>
        </thead>
        @foreach($generals as $general)
            <tr class="clickable-row" data-href="{{url("/general/subjects/edit/{$general->id}/")}}" >
                <td>{{ $general->title }}</td>
                <td>{{ $general->data ? $general->data->inn : "" }}</td>
                <td>{{ $general->user ? $general->user->name : "" }}</td>
                <td>{{ \App\Models\Clients\GeneralSubjects::STATUS_WORK[$general->status_work_id] }}</td>
            </tr>
        @endforeach

    </table>
@else
    {{ trans('form.empty') }}
@endif