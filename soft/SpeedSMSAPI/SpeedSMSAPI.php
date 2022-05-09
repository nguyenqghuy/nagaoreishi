<?php
/**
 * Created by PhpStorm.
 * User: SpeedSMS
 * Date: 9/20/15
 * Time: 5:06 PM
 */

class SpeedSMSAPI {
    private $ROOT_URL = "http://api.speedsms.vn/index.php";
    private $accessToken = "Your api access token";

    public function getUserInfo() {
        $results = '';
        $url = $this->ROOT_URL.'/user/info';
        $headers = array();
        $headers[] = 'Accept: application/json';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->accessToken.':x');
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $str) use (&$results) {
            $results .= $str;
            return strlen($str);
        });

        curl_exec($ch);
        if(curl_errno($ch)) {
            return null;
        }
        else {
            curl_close($ch);
        }


        return json_decode($results, true);
    }

    public function sendSMS($to, $smsContent) {
        if (!is_array($to) || empty($to) || empty($smsContent))
            return null;

        $json = json_encode(['to' => $to, 'content' => $smsContent]);

        $headers[] = 'Content-type: application/json';

        $url = $this->ROOT_URL.'/sms/send';
        $http = curl_init($url);
        curl_setopt($http, CURLOPT_HEADER, false);
        curl_setopt($http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($http, CURLOPT_POSTFIELDS, $json);
        curl_setopt($http, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($http, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($http, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($http, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($http, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($http, CURLOPT_USERPWD, $this->accessToken.':x');
        $result = curl_exec($http);
        if(curl_errno($http))
        {
            return null;
        }
        else
        {
            curl_close($http);
            return json_decode($result, true);
        }
    }

    public function getSMSStatus($tranId) {
        $results = '';
        $url = $this->ROOT_URL.'/sms/status/'.$tranId;
        $headers = array();
        $headers[] = 'Accept: application/json';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->accessToken.':x');
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $str) use (&$results) {
            $results .= $str;
            return strlen($str);
        });

        curl_exec($ch);
        if(curl_errno($ch)) {
            return null;
        }
        else {
            curl_close($ch);
        }


        return json_decode($results, true);
    }
} 