<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contracts\Subjects;

class CustomsuggestsController extends Controller {

    CONST ALLOWED_NAMES = [
        'suggest_physical',
        'suggest_jure',
    ];

    public function suggest($name, $type, Request $request) {
        if (in_array($name, self::ALLOWED_NAMES) && method_exists($this, $name)) {
            return $this->$name($type, $request);
        }
    }

    private function suggest_physical($type, Request $request) {
        $suggestions = app()->make('\App\Http\Controllers\SuggestsController')->callAction('suggest', [$type, $request]);
        $query = $request->all()['query'] ?? '';

        $subjects = Subjects::where('title', 'like', '%' . $query . '%')
                        ->where('title', '!=', '')
                        ->whereNull('inn')
                        ->whereNull('kpp')
                        ->take(5)->get();

        foreach ($subjects as $subject) {
            $suggestions['suggestions'][] = [
                'value' => $subject->title,
                'data' => [
                    'doc_serie' => $subject->doc_serie,
                    'doc_number' => $subject->doc_number,
                    'email' => $subject->email,
                    'phone' => $subject->phone,
                ],
            ];
        }
        return $suggestions;
    }

    private function suggest_jure($type, Request $request) {
        $suggestions = app()->make('\App\Http\Controllers\SuggestsController')->callAction('suggest', [$type, $request]);
        $query = $request->all()['query'] ?? '';

        $subjects = Subjects::where('title', 'like', '%' . $query . '%')
                        ->where('title', '!=', '')
                        ->whereNull('doc_serie')
                        ->whereNull('doc_number')
                        ->take(5)->get();

        foreach ($subjects as $subject) {
            $suggestions['suggestions'][] = [
                'value' => $subject->title,
                'data' => [
                    'inn' => $subject->inn,
                    'kpp' => $subject->kpp,
                    'email' => $subject->email,
                    'phone' => $subject->phone,
                ],
            ];
        }
        return $suggestions;
    }

}
