<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Helpers\ResultGenerate;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\VehicleColor;
use Illuminate\Http\Request;

class ColorsController extends Controller
{
    public function ColorsPage()
    {
        $colors = VehicleColor::orderBy('title')->get();
        return view('directories.auto.form.colors.colors', [
            'colors' => $colors
        ]);
    }

    public function ColorEditPage(Request $request)
    {
        $colorId = $request->colorId;
        $color = VehicleColor::findOrFail($colorId);
        return view('directories.auto.form.colors.createOrEdit', [
            'color' => $color
        ]);
    }

    public function ColorCreatePage()
    {
        return view('directories.auto.form.colors.createOrEdit');
    }

    public function ColorSave(Request $request)
    {
        $colorId = (int)$request->colorId;
        $colorName = $request->colorName;

        if (empty($colorName)) {
            return ResultGenerate::Error('Название не может быть пустым!');
        }

        if ($colorId > 0) {
            $color = VehicleColor::find($colorId);
        } else {
            $color = new VehicleColor();
            $colorLast = VehicleColor::latest('id')->first();
            $color->isn = ($colorLast) ? $colorLast->isn + 1 : 1;
        }
        $color->title = $colorName;
        $color->save();

        return ResultGenerate::Success();
    }

    public function ColorDelete(Request $request)
    {
        $colorId = $request->colorId;
        $color = VehicleColor::findOrFail($colorId);
        $color->delete();
        return ResultGenerate::Success();
    }
}