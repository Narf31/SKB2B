<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Helpers\ResultGenerate;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    public function ModelsPage()
    {
        $categories = VehicleCategories::orderBy('title')->get();
        $marks = VehicleMarks::orderBy('title')->get();
        return view('directories.auto.form.models.models', [
            'marks' => $marks,
            'categories' => $categories

        ]);
    }

    public function GetMarkModels(Request $request): array
    {
        $markIsn = $request->markIsn;
        $models = VehicleModels::where('mark_id', $markIsn)->orderBy('title')->get()->toArray();
        return $models;
    }

    public function ModelEditPage(Request $request)
    {
        $modelId = $request->modelId;
        $model = VehicleModels::findOrFail($modelId);
        $marks = VehicleMarks::orderBy('title')->get();
        return view('directories.auto.form.models.createOrEdit', [
            'model' =>  $model,
            'marks' => $marks
        ]);
    }

    public function ModelCreatePage()
    {
        $marks = VehicleMarks::orderBy('title')->get();
        return view('directories.auto.form.models.createOrEdit', [
            'marks' => $marks
        ]);
    }

    public function ModelSave(Request $request)
    {
        $markIsn = $request->markIsn;
        $modelName = $request->modelName;
        $modelRisky = $request->modelRisky;
        $modelId = (int)$request->modelId;

        if (empty($modelName)) {
            return ResultGenerate::Error('Название не может быть пустым!');
        }

        if ($modelId > 0) {
            $model = VehicleModels::find($modelId);
        } else {
            $model = new VehicleModels();
            $modelLast = VehicleModels::latest('id')->first();
            $model->isn = ($modelLast) ? $modelLast->isn + 1 : 1;
        }
        $model->mark_id = $markIsn;
        $model->is_risky = $modelRisky === 'true' ? 1 : 0;
        $model->title = $modelName;
        $model->save();

        return ResultGenerate::Success();
    }

    public function ModelDelete(Request $request)
    {
        $modelId = $request->modelId;
        $model = VehicleModels::findOrFail($modelId);
        $model->delete();
        return ResultGenerate::Success();
    }
}