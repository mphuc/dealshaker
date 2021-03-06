<?php
/**
 * Created by PhpStorm.
 * User: SpeedSMS
 * Date: 9/20/15
 * Time: 5:06 PM
 */

class SpeedSMSAPI {
    const SMS_TYPE_QC = 1; // loai tin nhan quang cao
    const SMS_TYPE_CSKH = 2; // loai tin nhan cham soc khach hang
    const SMS_TYPE_BRANDNAME = 3; // loai tin nhan brand name cskh

    private $ROOT_URL = "http://api.speedsms.vn/index.php";
    private $accessToken = "tzboay3_uL6CLjHzfkxRDwm6pQSsyx4t";

    public function getUserInfo() {
        $url = $this->ROOT_URL.'/user/info';
        $headers = array('Accept: application/json');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->accessToken.':x');

        $results = curl_exec($ch);

        if(curl_errno($ch)) {
            return null;
        }
        else {
            curl_close($ch);
        }
        return json_decode($results, true);
    }

    public function sendSMS($to, $smsContent, $smsType, $brandName, $dlr) {
        if (!is_array($to) || empty($to) || empty($smsContent))
            return null;

        $type = SpeedSMSAPI::SMS_TYPE_CSKH;
        if (!empty($smsType))
            $type = $smsType;

        if ($type < 1 && $type > 3)
            return null;

        if ($type == 3 && empty($brandName))
            return null;

        if (strlen($brandName) > 11)
            return null;

        if ($type != 2)
            $dlr = 0;


        $json = json_encode(array('to' => $to, 'content' => $smsContent, 'sms_type' => $type, 'brandname' => $brandName, 'dlr' => $dlr));

        $headers = array('Content-type: application/json');

        $url = $this->ROOT_URL.'/sms/send';
        $http = curl_init($url);
        curl_setopt($http, CURLOPT_HEADER, false);
        curl_setopt($http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($http, CURLOPT_POSTFIELDS, $json);
        curl_setopt($http, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($http, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($http, CURLOPT_VERBOSE, 0);
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
        $url = $this->ROOT_URL.'/sms/status/'.$tranId;
        $headers = array('Accept: application/json');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->accessToken.':x');

        $result = curl_exec($ch);
        if(curl_errno($ch))
        {
            return null;
        }
        else
        {
            curl_close($ch);
            return json_decode($result, true);
        }
    }
} 