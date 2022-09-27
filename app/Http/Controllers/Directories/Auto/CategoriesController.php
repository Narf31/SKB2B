<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Helpers\ResultGenerate;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function CategoriesPage()
    {
        $categories = VehicleCategories::orderBy('title')->get();
        return view('directories.auto.form.categories.categories', [
            'categories' => $categories
        ]);
    }

    public function CategoryEditPage(Request $request)
    {
        $categoryId = $request->categoryId;
        $category = VehicleCategories::findOrFail($categoryId);
        return view('directories.auto.form.categories.createOrEdit', [
            'category' => $category
        ]);
    }

    public function CategoryCreatePage()
    {
        return view('directories.auto.form.categories.createOrEdit');
    }

    public function CategorySave(Request $request)
    {
        $categoryId = (int)$request->categoryId;
        $categoryName = $request->categoryName;
        $categoryActual = $request->categoryActual;

        if (empty($categoryName)) {
            return ResultGenerate::Error('Название не может быть пустым!');
        }

        if ($categoryId > 0) {
            $category = VehicleCategories::find($categoryId);
        } else {
            $category = new VehicleCategories();
            $categoryLast = VehicleCategories::latest('id')->first();
            $category->isn = ($categoryLast) ? $categoryLast->isn + 1 : 1;
        }
        $category->title = $categoryName;
        $category->is_actual = $categoryActual === 'true' ? 1 : 0;
        $category->save();

        return ResultGenerate::Success();
    }

    public function CategoryDelete(Request $request)
    {
        $categoryId = $request->categoryId;
        $category = VehicleCategories::findOrFail($categoryId);
        $categoryMark = VehicleMarks::where('category_id', $category->isn)->first();
        if (empty($categoryMark)) {
            $category->delete();
            return ResultGenerate::Success();
        }
        return ResultGenerate::Error('В категории есть марки. Перенесите их!');
    }
}