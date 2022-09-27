<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\TableColumn;
use App\Models\Account\Users2Columns;
use Auth;
use DB;
use Illuminate\Http\Request;

class TableSettingController extends Controller
{

    public function edit($table_key)
    {
        $visibility = null;
        $controller = null;
        $user = Auth::user();

        $user_columns = $user->columns()->get()
            ->where('table_key', $table_key)
            ->sortBy('pivot.orders');

        $user_columns_all = TableColumn::query()
            ->where('table_key', $table_key)
            ->get();

        $other_columns_keys = TableColumn::query()
            ->whereNotIn('column_key', $user_columns->pluck('column_key'))
            ->pluck('column_key');


        $table_columns = TableColumn::all()
            ->where('table_key', $table_key)
            ->whereIn('column_key', $other_columns_keys);




        return view('account.table_edit', [
            'controller' => $controller,
            'visibility' => $visibility,
            'user_columns' => $user_columns,
            'table_columns' => $table_columns,
            'table_key' => $table_key,
        ]);

    }

    public function save($table_key, Request $request)
    {
        $result = ['status' => 'error'];


        $user = Auth::user();
        $data = $request->all();

        $i = 0;

        foreach ($data as $column_id => $val) {

            $data[$column_id] = $i;
            $i++;

        }

        DB::query()->from('users2columns')
            ->join('table_columns', 'table_columns.id', 'users2columns.column_id')
            ->where('users2columns.user_id', $user->id)
            ->whereIn('table_columns.table_key', [$table_key, '_' . $table_key])->delete();

        $table_columns = TableColumn::all()
            ->whereIn('table_key', [$table_key, '_' . $table_key])
            ->whereIn('id', array_keys($data));

        foreach ($table_columns as $table_column) {
            $user->columns()->attach([
                $table_column->id => [
                    'orders' => $data[$table_column['id']],
                ]
            ]);
        }

        $result['status'] = 'ok';

        return response()->json($result);
    }


}