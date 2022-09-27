<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Helpers\ResultGenerate;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    public function MarksPage()
    {
        $categories = VehicleCategories::orderBy('title')->get();
        return view('directories.auto.form.marks.marks', [
            'categories' => $categories
        ]);
    }

    public function GetCategoryMarks(Request $request): array
    {
        $categoryIsn = $request->categoryIsn;
        $marks = VehicleMarks::where('category_id', $categoryIsn)->orderBy('title')->get()->toArray();
        return $marks;
    }

    public function MarkEditPage(Request $request)
    {
        $markId = $request->markId;
        $mark = VehicleMarks::findOrFail($markId);
        $categories = VehicleCategories::orderBy('title')->get();
        return view('directories.auto.form.marks.createOrEdit', [
            'mark' =>  $mark,
            'categories' => $categories
        ]);
    }

    public function MarkCreatePage()
    {
        $categories = VehicleCategories::orderBy('title')->get();
        return view('directories.auto.form.marks.createOrEdit', [
            'categories' => $categories
        ]);
    }

    public function MarkSave(Request $request)
    {
        $categoryIsn = $request->categoryIsn;
        $markName = $request->markName;
        $markId = (int)$request->markId;

        if (empty($markName)) {
            return ResultGenerate::Error('Название не может быть пустым!');
        }

        if ($markId > 0) {
            $mark = VehicleMarks::find($markId);
        } else {
            $mark = new VehicleMarks();
            $markLast = VehicleMarks::latest('id')->first();
            $mark->isn = ($markLast) ? $markLast->isn + 1 : 1;
        }
        $mark->category_id = $categoryIsn;
        $mark->title = $markName;
        $mark->save();

        return ResultGenerate::Success();
    }

    public function MarkDelete(Request $request)
    {
        $markId = $request->markId;
        $mark = VehicleMarks::findOrFail($markId);
        $markModel = VehicleModels::where('mark_id', $mark->isn)->first();
        if (empty($markModel)) {
            $mark->delete();
            return ResultGenerate::Success();
        }
        return ResultGenerate::Error('В марке есть модели. Перенесите их!');
    }
}