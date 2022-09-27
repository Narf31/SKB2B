<?php

namespace App\Classes\Export;


use App\Classes\Export\Replacers\ExcelReplacer;
use App\Classes\Export\Replacers\WordReplacer;
use App\Models\Settings\TemplateCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;


class ExportSenderPdf{

    private $path = null;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getHTMLPbf($html, $name)
    {



    }

    public static function word_to_pdf($contents, $name){

    }

    public static function html_to_pdf($contents, $name){

        $url = "https://v2.convertapi.com/convert/html/to/pdf?Secret=ALzo8iJ7D6egiC4a";
        $params = [
            'Parameters' => [
                0 => [
                    'Name' => "File",
                    'FileValue' => [
                        'Name' => "$name.html",

                        'Data' => $contents
                    ]
                ],
                1=> [
                    "Name" => "PageOrientation",
                    "Value" => "landscape"
                ],
                2=> [
                    "Name" => "PageSize",
                    "Value" => "a4"
                ],
                3=> [
                    "Name" => " Footer",
                    "Value" => "pageNumber страниц из totalPages"
                ],

            ]
        ];

        $curl = curl_init();

        $headers = [];
        $headers[] = "Content-Type: application/json";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \GuzzleHttp\json_encode($params),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //dd($response);

        return $response;
    }


}