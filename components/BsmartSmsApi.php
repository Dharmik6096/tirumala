<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;
use linslin\yii2\curl\Curl;

class BsmartSmsApi extends Component {

    private $serverUrl = '193.105.74.58';
    private $username = 'Everest_Instru';
    private $password = 'voda1234';
    private $sender = 'EIPLMC';
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

    public function getStatusMsg($status) {
        return $this->msgs[$status];
    }

    public function sendSmsPOSTNew($mobileNumber, $message, $language = FALSE) {
        $url = "http://www.smsidea.co.in/SmsStatuswithId.aspx";
        $param = [
            'mobile' => '9712147065',
            'pass' => 'SYSID',
            'senderid' => 'SMSBUZ',
            'to' => $mobileNumber,
            'msg' => $message,
        ];
        if ($language) {
            $param['msgtype'] = 'uc';
        }
        $curl = new Curl();
        $response = $curl->setHeaders([
                    'ContentType' => 'application/x-www-form-urlencoded',
                ])->setPostParams($param)
                ->post($url);
        $result = $curl->response;
        return $result;
    }

}
