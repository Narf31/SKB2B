<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuSettingsController extends Controller {

    public function update(Request $request) {
        $user = auth()->user();
        if ($user) {
            $user->settings = [
                'menu' => $request->menu ?? '',
                'menu_section' => $request->menu_section ?? '',
            ];
            $user->save();
        }
    }

}
