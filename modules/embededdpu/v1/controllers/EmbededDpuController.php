<?php

namespace app\modules\embededdpu\v1\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use ReflectionClass;
use DateTime;
use app\modules\vendorapi\models\TblVendorApiData;
use webvimark\modules\UserManagement\models\User;
use app\modules\vendorapi\Vendorapi;
use app\models\TblUserOrganizationMapping;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\installation\models\TblAndroidInstallation;
use app\modules\installation\models\TblAndroidInstallationDetails;
use app\modules\embededdpu\controllers\RestController;
use app\modules\organisation\models\TblMccPlant;

/**
 * Default controller for the `vendorapi` module
 */
class EmbededDpuController extends RestController {

    public function actionStartUp() {
        $req_data = Yii::$app->request->getRawBody();
        $stationId = !empty($req_data['station_id']) ? $req_data['station_id'] : '0';
        $sp_name = 'sp_app_embeded_dpu_v1_start_up';
        $sp_param = [];
        $sp_param[] = $stationId;
        $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $data = [];
        if(!empty($response)) {
            $data = $response[0];
        }
        $this->response['data'] = $data;
        return $this->response;
    }

}
