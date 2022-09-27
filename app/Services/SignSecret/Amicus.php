<?php

namespace App\Services\SignSecret;



use App\Models\Settings\SettingsSystem;

class Amicus
{

    public static function signFile($path, $file_name)
    {

        $URL = SettingsSystem::getDataParam('amicus', 'url');
        $secret = SettingsSystem::getDataParam('amicus', 'secret');

        if(strlen($secret) > 0){
            $content = file_get_contents("{$path}{$file_name}");
            $hash = hash_hmac('sha512', $content, $secret);


            $headers = [];
            $headers[] = 'X-Hash: '.$hash;



            $file = fopen("{$path}{$file_name}", 'r');
            $size = filesize("{$path}{$file_name}");
            $fildata = fread($file,$size);

            $boundary = "--".uniqid();
            $delimiter = "--".$boundary;

            $data =  $delimiter . "\r\n"
                . 'Content-Disposition: form-data; name="file"; filename="' . $file_name . '"' . "\r\n"
                . 'Content-Type: text/plain' . "\r\n\r\n"
                . $fildata . "\r\n";

            $data .= $delimiter . "\r\n";


            $headers[] = 'Content-Type: multipart/form-data; boundary=' .$boundary;
            $headers[] = 'Content-Length: ' . strlen($data);

            $ch = curl_init($URL);

            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $data
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if($response){
                if(is_file("{$path}{$file_name}")){
                    unlink("{$path}{$file_name}");
                    file_put_contents("{$path}{$file_name}", $response);
                }
            }
        }




        return true;


    }


}