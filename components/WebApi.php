<?php

namespace app\components;

use yii;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;

class WebApi {

    private $serverUrl = 'http://52.32.190.89/tpi/eipl/';
    private $authentication = [
        'user' => ['userName' => 'eipl', 'password' => 'eipl123']
    ];
    public $apiurl = '';
    public $body = [];

    public function POSTDATA() {
        $this->body = array_merge($this->authentication, $this->body);
        return $this->PHPCURL();
    }

    public function GuzzleCURL() {
        $url = $this->serverUrl . $this->apiurl;
        $client = new GuzzleHttp\Client();
        $postData = [
            RequestOptions::JSON => [
                'user' =>
                ['userName' => 'eipl',
                    'password' => 'eipl123'],
                'metadata' => [
                    'organization' => [
                        'id' => 'TMD0011',
                        'name' => 'Thirumala',
                    ],
                    'chillingCenter' => [
                        'id' => '2068',
                        'name' => 'Annur',
                    ],
                    'route' => [
                        'id' => 'ROUTE1',
                        'name' => 'Route EIPL Test',
                    ],
                ],
                'collectionCenterList' => [[
                'name' => 'EIPL Test',
                'id' => 'TMCC01',
                'isActive' => true,
                'location' => 'bangalore',
                'operatorName' => 'balu',
                'operatorMobileNum' => '8095242818',
                'operatorCode' => '00001',
                'operatorEmailId' => 'test@gmail.com',
                'createdTime' => 1524655358296,
                'lastModifiedTime' => 1524655358296
                    ]
                ]
        ]];
        $resp = $client->request('POST', $url, $postData);
        return $resp->getBody();
    }

    public function PHPCURL() {
        $url = $this->serverUrl . $this->apiurl;
        $data = json_encode($this->body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-length: " . strlen($data)));
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

}
