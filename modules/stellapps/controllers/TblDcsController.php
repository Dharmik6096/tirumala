<?php

namespace app\modules\stellapps\controllers;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblRouteMappingSources;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\details\models\TblContactDetails;
use app\components\WebApi;

class TblDcsController extends \yii\web\Controller {

    public function actionIndex() {
        $dcs_model = new TblDcs();
        $dcs_data = $dcs_model->getNewDcs();
        $dcs_codes = array_column($dcs_data, 'dcs_code');
        $update = $dcs_model->updateDcs($dcs_codes);
        foreach ($dcs_data as $dcs) {
            $dcs->scenario = 'deactivate';
            try {
                $body = [];
                $metadata = [];
                $organization = [];
                $chillingCenter = [];
                $route = [];
                $collectionCenterList = [];
                $organization['id'] = $dcs->union_code;
                $organization['name'] = Yii::$app->general->getforeignkey($dcs->unionCode, 'union_name');
                $route_model = new TblRouteMappingSources();
                $route_model->from_dest = $dcs->dcs_code;
                $route_data = $route_model->getRouteDcsData();
                if (!empty($route_data)) {
                    $route['id'] = $route_data->route_code;
                    $route['name'] = Yii::$app->general->getforeignkey($route_data->routeCode, 'route_name');

                    $dcs_bmc_model = new TblDcsBmc();
                    $field = $route_data->to_type . '_code';
                    $dcs_bmc_model->$field = $route_data->to_dest;
                    $dcs_bmc_data = $dcs_bmc_model->getDcsBmcData($field);
                    if (!empty($dcs_bmc_data)) {
                        $chillingCenter['id'] = $dcs_bmc_data->bmc_code;
                        $chillingCenter['name'] = $dcs_bmc_data->bmc_name;

                        $metadata['organization'] = $organization;
                        $metadata['chillingCenter'] = $chillingCenter;
                        $metadata['route'] = $route;
                        $body['metadata'] = $metadata;

                        $contact_model = new TblContactDetails();
                        $contact_model->module_code = $dcs->dcs_code;
                        $contact_model->module_name = 'society';
                        $contact_data = $contact_model->getContactDetails();

                        $collectionCenterList['id'] = Yii::$app->general->getforeignkey($dcs->societyCodes, 'bipl_code');
                        $collectionCenterList['name'] = $dcs->dcs_name;
                        $collectionCenterList['isActive'] = $dcs->is_active == 1 ? 'True' : 'False';
                        $collectionCenterList['location'] = Yii::$app->general->getforeignkey($dcs->hamletCode, 'hamlet_name');
                        $collectionCenterList['operatorName'] = $contact_data->firstname;
                        $collectionCenterList['operatorMobileNum'] = $contact_data->mobile_no;
                        $collectionCenterList['operatorCode'] = '';
                        $collectionCenterList['operatorEmailId'] = $contact_data->email;
                        $collectionCenterList['createdTime'] = !empty($contact_data->created_at) ? strtotime($contact_data->created_at) : '';
                        $collectionCenterList['lastModifiedTime'] = !empty($contact_data->updated_at) ? strtotime($contact_data->updated_at) : '';
                        $body['collectionCenterList'][] = $collectionCenterList;
                        $api = new WebApi();
                        $api->apiurl = 'tmccs';
                        $api->body = $body;
                        if ($api->POSTDATA()->msg == 'Success!') {
                            $dcs->data_post_status = 2;
                        } else {
                            $dcs->data_post_status = 3;
                        }
                        $dcs->save();
                    }
                }
            } catch (\Exception $e) {
                $dcs->data_post_status = 3;
                $dcs->save();
            }
        }
    }

}
