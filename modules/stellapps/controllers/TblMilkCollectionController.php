<?php

namespace app\modules\stellapps\controllers;

use Yii;
use app\components\WebApi;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblRouteMappingSources;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\details\models\TblContactDetails;
use app\modules\collection\models\TblMilkCollection;

class TblMilkCollectionController extends \yii\web\Controller {

    public function actionIndex() {
        $milk_coll_model = new TblMilkCollection();
        $milk_coll_data = $milk_coll_model->getMilkCollData();
        $milk_coll_codes = array_column($milk_coll_data, 'milk_collection_code');
        $update = $milk_coll_model->updateMilkColl($milk_coll_codes);
        foreach ($milk_coll_data as $milk_coll) {
            $dcs_model = new TblDcs();
            $dcs_model->dcs_code = $milk_coll->dcs_code;
            $dcs = $dcs_model->getDcsMilkColl();

            try {
                $body = [];
                $metadata = [];
                $organization = [];
                $chillingCenter = [];
                $route = [];
                $collectionCenter = [];
                $collectionEntryList = [];
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

                        $contact_model = new TblContactDetails();
                        $contact_model->module_code = $dcs->dcs_code;
                        $contact_model->module_name = 'society';
                        $contact_data = $contact_model->getContactDetails();

                        $collectionCenter['id'] = Yii::$app->general->getforeignkey($dcs->societyCodes, 'bipl_code');
                        $collectionCenter['name'] = $dcs->dcs_name;

                        $metadata['collectionCenter'] = $collectionCenter;
                        $metadata['date'] = date('Y-m-d', strtotime($milk_coll->date_time_of_collection)); //Yii::$app->formatter->asDate($milk_coll->date_time_of_collection, DATE_FORMAT);
                        $metadata['shift'] = Yii::$app->general->getforeignkey($milk_coll->shiftCode, 'shift');

                        $collectionEntryList['farmerId'] = $milk_coll->member_code;
                        $collectionEntryList['milkType'] = strtoupper(substr(Yii::$app->general->getforeignkey($milk_coll->milkTypeCode, 'animal_type_name'), 0, 1));
                        $collectionEntryList['milkQuantity'] = $milk_coll->qty;
                        $collectionEntryList['fat'] = $milk_coll->fat;
                        $collectionEntryList['snf'] = $milk_coll->snf;
                        $collectionEntryList['rate'] = $milk_coll->rtpl;
                        $collectionEntryList['amount'] = $milk_coll->amount;
                        $collectionEntryList['collectionTime'] = 1524655358296;
                        $collectionEntryList['awm'] = '';
                        $collectionEntryList['clr'] = $milk_coll->clr;
                        $collectionEntryList['status'] = $milk_coll->status;
                        $collectionEntryList['mode'] = $milk_coll->qty_mode;
                        $collectionEntryList['qualityTime'] = 1524655358296;
                        $collectionEntryList['quantityTime'] = 1524655358296;
                        $collectionEntryList['uom'] = '';
                        $collectionEntryList['numberOfCans'] = $milk_coll->no_of_can;
                        $collectionEntryList['milkQuality'] = strtoupper(substr(Yii::$app->general->getforeignkey($milk_coll->milkQualityTypeCode, 'milk_quality_type_name'), 0, 1));
                        $collectionEntryList['qualityMode'] = $milk_coll->qlty_auto;
                        $collectionEntryList['quantityMode'] = $milk_coll->qty_auto;
                        $collectionEntryList['conductivity'] = '';
                        $collectionEntryList['density'] = '';
                        $collectionEntryList['salt'] = '';
                        $collectionEntryList['lactose'] = '';
                        $collectionEntryList['pH'] = '';
                        $collectionEntryList['sampleNumber'] = $milk_coll->sample_no;
                        $body['metadata'] = $metadata;
                        $body['collectionEntryList'] = $collectionEntryList;
                        $api = new WebApi();
                        $api->apiurl = 'tmccs/farmercollections';
                        $api->body = $body;
                        if ($api->POSTDATA()->msg == 'Success!') {
                            $milk_coll->data_post_status = 2;
                        } else {
                            $milk_coll->data_post_status = 3;
                        }
                        $milk_coll->save();
                    }
                }
            } catch (\Exception $e) {
                $milk_coll->data_post_status = 3;
                $milk_coll->save();
            }
        }
    }

}
