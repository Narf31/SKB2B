<table class="tov-table">
    <thead>
        <tr>
            <th><a href="#">{{ trans('settings/incomes_expenses_categories.title') }}</a></th>
            <th><a href="#">{{ trans('settings/incomes_expenses_categories.is_actual') }}</a></th>
            <th><a href="#">{{ trans('settings/incomes_expenses_categories.type') }}</a></th>
        </tr>
    </thead>
    @if(sizeof($incomes_expenses_categories))
        @foreach($incomes_expenses_categories as $income_expense_category)
            <tr onclick="openFancyBoxFrame('{{ url("/settings/incomes_expenses_categories/$income_expense_category->id/edit") }}')">
                <td>{{ $income_expense_category->title }}</td>
                <td>{{ ($income_expense_category->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>
                <td>{{ \App\Models\Settings\IncomeExpenseCategory::TYPE[$income_expense_category->type] }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3" class="text-center">Пока не добавлено ни одной категории</td>
        </tr>
    @endif
</table>
