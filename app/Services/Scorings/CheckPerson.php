<?php

namespace App\Services\Scorings;

use Mockery\Exception;

class CheckPerson
{

    public $STATE = false;

    public $URL = "https://b2b-api.spectrumdata.ru/b2b/api/v1/";

    private $user = "";
    private $pass = "";
    private $domain = "";
    private $report_type = "";
    private $token = "";

    public function __construct()
    {
        $this->user = env('CheckPerson_user', '');
        $this->pass = env('CheckPerson_pass', '');
        $this->domain = env('CheckPerson_domain', '');
        $this->report_type = env('CheckPerson_report_type', '');

        if(strlen($this->user) > 0 && strlen($this->pass) && strlen($this->domain) && strlen($this->report_type)){
            $this->token = self::generateToken($this->user, $this->pass);
            $this->STATE = true;
        }
    }

    private function send($data, $fun, $metod = 'POST', $get_data = ''){


        $curl = curl_init();

        $headers = [];
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = 'Accept: application/json';

        if(strlen($this->token) > 5){
            $headers[] = 'Authorization: AR-REST '.$this->token;

        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL.$fun.$get_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $metod,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

        }

        return \GuzzleHttp\json_decode($response);
    }

    private function generateToken($username, $password)
    {
        $user = $username;
        $pass = $password;
        $stamp = time();
        $age = 60*60*24;
        $passHash = base64_encode(md5($pass, true));
        $saltedHash = base64_encode(md5($stamp.':'.$age.':'.$passHash, true));
        $token = base64_encode(implode(':', [$user, $stamp, $age, $saltedHash]));

        return $token;
    }

    public function getDataInfo($array)
    {
        $report_uid = "{$this->report_type}@{$this->domain}";
        $REPORT_TYPE_UID = urlencode($report_uid);

        $json = [
            'queryType' => "MULTIPART",
            'query' => " ",
            'data' => $array,
        ];

        $json = \GuzzleHttp\json_encode($json);

        $result = $this->send($json, "user/reports/{$REPORT_TYPE_UID}/_make", "POST");
        if(isset($result->state) && $result->state == 'ok' && isset($result->data)){

            return $this->getReportUid($result->data[0]->uid);
        }

        return $result;
    }

    public function getReportUid($report_uid)
    {
        $result = $this->send(null, "user/reports/{$report_uid}?_content=true", "GET");
        return ($result->data[0])?:null;
    }

}