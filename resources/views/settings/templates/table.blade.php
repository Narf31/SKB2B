@if(sizeof($templates))
    <table class="tov-table">
        <thead>
        <tr>
            <th><a href="#">{{ trans('settings/templates.title') }}</a></th>
            <th><a href="#">{{ trans('settings/templates.file') }}</a></th>
            <th><a href="#">Категория</a></th>
            <th><a href="#">Для поставщика</a></th>
        </tr>
        </thead>
        @foreach($templates as $template)
            <tr onclick="openFancyBoxFrame('{{ url("/settings/templates/$template->id/edit") }}')">
                <td>{{ $template->title }}</td>
                <td>
                    @if($template->file)
                        {{ $template->file->original_name }}
                    @else
                        Не загружен
                    @endif
                </td>
                <td>{{ $template->category ? $template->category->title : "" }}</td>

                <td>
                    @if($template->category->has_supplier)
                        {{$template->supplier ? $template->supplier->title : "Универсальный"}}
                    @else
                        Не требуется выбор поставщика
                    @endif

                </td>
            </tr>
        @endforeach
    </table>
@else
    {{ trans('form.empty') }}
@endif