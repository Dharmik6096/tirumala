<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;
use yii\helpers\Html;

class SmsApi extends Component {

    private $authKey='67c97bda8a5972ad84b365bacf6e1b';
    private $serverUrl='216.245.209.132';
    private $senderId="PCDFLK";
    private $routeId="1";


    public function sendsmsGET($mobileNumber,$senderId,$routeId,$message)
    {

        $getData = 'mobileNos='.$mobileNumber.'&message='.urlencode($message).'&senderId='.$senderId.'&routeId='.$routeId;

        //API URL
        $url="http://".$serverUrl."/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$authKey."&".$getData;


        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0

        ));


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        return $output;
    }

    public function sendsmsPOST($mobileNumber,$message)
    {

             $postData = array(


            'mobileNumbers' => $mobileNumber,        
            'smsContent' => $message,
            'senderId' => $this->senderId,
            'routeId' => $this->routeId,		
            "smsContentType" =>'english'
        );


        $data_json = json_encode($postData);


        $url="http://".$this->serverUrl."/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$this->authKey;


        // init the resource
        $ch = curl_init();



        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json','Content-Length: ' . strlen($data_json)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }
   
}
