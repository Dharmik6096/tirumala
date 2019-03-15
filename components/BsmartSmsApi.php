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

    private $serverUrl = 'www.smsidea.co.in';
    private $username = '9712147065';
    private $password = 'SYSID';
    private $sender = 'EIPLMC';
    private $msgs = ['0' => 'Successfully Sent'];


    public function getStatusMsg($status) {
        return $this->msgs[$status];
    }

    public function sendSmsPOST($mobileNumber, $message, $language = FALSE) {
        $url = "http://" . $this->serverUrl . "/SmsStatuswithId.aspx";
        $param = [
            'mobile' => $this->username,
            'pass' => $this->password,
            'senderid' => $this->sender,
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
