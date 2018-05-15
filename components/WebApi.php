<?php

namespace app\components;

use yii;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;

class WebApi {

    private $serverUrl = '193.105.74.58';
    private $username = 'Everest_Instru';
    private $password = 'voda1234';
    private $sender = 'PCDFLK';
    private $msgs = ['0' => 'Successfully Sent'];

    public function sendSmsPOST($mobileNumber, $message, $language = FALSE) {
        $url = "http://" . $this->serverUrl . "/api/v3/sendsms/json";
        $postData = [
            RequestOptions::JSON => [
                'authentication' => ['username' => $this->username, 'password' => $this->password],
                'messages' => [
                    [
                        'sender' => $this->sender,
                        'text' => $message,
                        'recipients' => [['gsm' => $mobileNumber]]
                    ]
                ],
        ]];
        if ($language) {
            $postData['json']['messages'][0]['datacoding'] = '8';
        }
        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', $url, $postData);
        return $res->getBody();                 // {"type":"User"...'
    }

}
