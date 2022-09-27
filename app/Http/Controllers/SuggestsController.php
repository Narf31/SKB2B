<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuggestsController extends Controller {

    CONST TOKEN = '2db5a8f85ea762d911f7bf65b7371a076a7aebae';
    CONST API_URL = 'http://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/';
    CONST ALLOWED_METHODS = [
        'fio',
        'party',
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function suggest($type, Request $request) {

        if (in_array($type, self::ALLOWED_METHODS)) {
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => array(
                        'Content-type: application/json',
                        'Authorization: Token ' . self::TOKEN
                    ),
                    'content' => json_encode($request->all()),
                ),
            );

            $context = stream_context_create($options);
            $result = json_decode(file_get_contents(self::API_URL . $type, false, $context), 1);
        }
        return $result ?? 'Error - unknown method';
    }

}
