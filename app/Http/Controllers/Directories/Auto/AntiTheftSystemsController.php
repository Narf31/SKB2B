<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Helpers\ResultGenerate;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use Illuminate\Http\Request;

class AntiTheftSystemsController extends Controller
{
    public function AntiTheftSystemsPage()
    {
        $antiTheftSystems = VehicleAntiTheftSystem::orderBy('title')->get();
        return view('directories.auto.form.anti_theft_systems.anti_theft_systems', [
            'antiTheftSystems' => $antiTheftSystems
        ]);
    }

    public function AntiTheftSystemEditPage(Request $request)
    {
        $antiTheftSystemId = $request->antiTheftSystemId;
        $antiTheftSystem = VehicleAntiTheftSystem::findOrFail($antiTheftSystemId);
        return view('directories.auto.form.anti_theft_systems.createOrEdit', [
            'antiTheftSystem' => $antiTheftSystem
        ]);
    }

    public function AntiTheftSystemCreatePage()
    {
        return view('directories.auto.form.anti_theft_systems.createOrEdit');
    }

    public function AntiTheftSystemSave(Request $request)
    {
        $antiTheftSystemId = (int)$request->antiTheftSystemId;
        $antiTheftSystemName = $request->antiTheftSystemName;

        if (empty($antiTheftSystemName)) {
            return ResultGenerate::Error('Название не может быть пустым!');
        }

        if ($antiTheftSystemId > 0) {
            $antiTheftSystem = VehicleAntiTheftSystem::find($antiTheftSystemId);
        } else {
            $antiTheftSystem = new VehicleAntiTheftSystem();
            $antiTheftSystemLast = VehicleAntiTheftSystem::latest('id')->first();
            $antiTheftSystem->isn = ($antiTheftSystemLast) ? $antiTheftSystemLast->isn + 1 : 1;
        }
        $antiTheftSystem->title = $antiTheftSystemName;
        $antiTheftSystem->save();

        return ResultGenerate::Success();
    }

    public function AntiTheftSystemDelete(Request $request)
    {
        $antiTheftSystemId = $request->antiTheftSystemId;
        $antiTheftSystem = VehicleAntiTheftSystem::findOrFail($antiTheftSystemId);
        $antiTheftSystem->delete();
        return ResultGenerate::Success();
    }
}