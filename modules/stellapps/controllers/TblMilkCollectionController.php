<?php

namespace app\modules\stellapps\controllers;

use Yii;
use app\components\WebApi;

class TblMilkCollectionController extends \yii\web\Controller {

    public function actionIndex() {
        $api = new WebApi();
        $api->apiurl = 'tmccs';
        $api->body = [
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
        ];
        var_dump($api->POSTDATA());
    }

}
