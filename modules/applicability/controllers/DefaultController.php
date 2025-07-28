<?php

namespace app\modules\applicability\controllers;

use Yii;
use yii\web\Controller;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblRouteMappingSources;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use ReflectionClass;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\usermanagement\models\User;
use app\modules\payment\models\TblPaymentCycleApplicability;
use yii\db\Query;

/**
 * Default controller for the `applicability` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionLoadSociety() {
        $dcs = [];
        $flag = Yii::$app->request->post('flag');
        $id = Yii::$app->request->post('id');
        $union_code = Yii::$app->request->post('ucode');
        $field_name = Yii::$app->request->post('field');
        $field_code = Yii::$app->request->post('fcode');
        $top_section = Yii::$app->request->post('top_section');
        $select_from_all = Yii::$app->request->post('select_from_all');
        $payment = Yii::$app->request->post('payment');
        $module = Yii::$app->getModule('applicability');
        $model_name = str_replace('_', '\\', Yii::$app->request->post('mname'));
        $module->model = new $model_name();
        $module->field_name = $field_name;
        $module->field_value = $field_code;
        $module->select_from_all = $select_from_all;
        $ratechart = Yii::$app->request->post('ratechart');
        $module->shift_type = Yii::$app->request->post('shift_type');
        $wef_date = !empty(Yii::$app->request->post('wef_date')) ? date('Y-m-d', strtotime(Yii::$app->request->post('wef_date'))) : '';
        $returnQuery = TRUE;
        $values = $module->getDcs($top_section, $wef_date, $returnQuery);

        $dcsalert = [];
        $removedcs = [];
        //echo $payment; exit;
        if ($payment) {
            //$list=$module->model->getPaymentCycleDcs($module->field_value);
            //$list=  ArrayHelper::getColumn($list, 'dcs_code');
            //$values=  array_values($list);
            //$values=[];
            //Comment as Set Validation from DB Side: Hardik
//            $dcsalertarray = $module->getDcsAlert($union_code, $id);
//            $dcsalert = $dcsalertarray[0];
//            $removedcs = $dcsalertarray[1];
            //var_dump($values);exit;
        } else if ($ratechart) {
            //$list=$module->model->getPaymentCycleDcs($module->field_value);
            //$list=  ArrayHelper::getColumn($list, 'dcs_code');
            //$values=  array_values($list);
            //$values=[];
            //Comment as Set Validation from DB Side: Hardik
//            $dcsalertarray = $module->getDcsAlertRateChart($union_code, $id, $wef_date, $module->shift_type);
//            $dcsalert = $dcsalertarray[0];
//            $removedcs = $dcsalertarray[1];
            //var_dump($values);exit;
        }
        switch ($flag) {
            case 'society':
                $dcs = $module->loadUnionDcs($union_code, $values);
                break;
            case 'routes':
                //$routes=new TblRoutes();
                //$routes=$routes->find(['route_code'=>$id])->one();
                //$dcs=  TblDcs::find()->where(['route_code'=>$id,'is_active'=>1])->andWhere(['not in','dcs_code',$values])->all();
//                $dcs = TblRouteMappingSources::find()->select(['tbl_dcs.dcs_code', 'tbl_dcs.dcs_name'])->where(['tbl_route_mapping_sources.route_code' => $id, 'tbl_route_mapping_sources.is_active' => 1])->andWhere(['not in', 'tbl_route_mapping_sources.from_dest', $values])->joinWith(['dcsCode'])->all();
                $route_model = new TblRouteMappingSources();
                $dcs = $route_model->getRouteDCS($id, $values);
                break;
            case 'plants':
                break;
            case 'mcc':
                $dcs_model = new TblDcs();
                $dcs = $dcs_model->getMccDCS($id, TRUE, $values);
                break;
            default :
        }
        //echo 'here';exit;
        $dcs_list = ArrayHelper::map($dcs, 'dcs_code', function($dcs) {
                    return $dcs->ref_code . '-' . $dcs->dcs_name;
                });
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode(['status' => 'success', 'data' => $dcs_list, 'dcsalert' => $dcsalert, 'dcsarray' => $removedcs]);
    }

    public function actionLoadFilterData() {
        $filter_data = [];
        $union_code = Yii::$app->request->post('ucode');
        $flag = Yii::$app->request->post('flag');
        $filters = Json::decode(Yii::$app->request->post('filters'));
        $filter = !empty(Yii::$app->request->post('filter_type')) ? Yii::$app->request->post('filter_type') : '';

        $model_name = str_replace('_', '\\', Yii::$app->request->post('mname'));
        $field_name = Yii::$app->request->post('field');
        $field_code = Yii::$app->request->post('fcode');
        $login_type = Yii::$app->request->post('login_type');
        $model = new $model_name();
        $wef_date = !empty(Yii::$app->request->post('wef_date')) ? date('Y-m-d', strtotime(Yii::$app->request->post('wef_date'))) : '';
        $isCheck = Yii::$app->request->post('checkdate');
        $periodic_applicability = Yii::$app->request->post('periodic_applicability');
        $is_bulk_notification = Yii::$app->request->post('is_bulk_notification');
        $check_applicability_with_field_name = Yii::$app->request->post('check_applicability_with_field_name');
        $where = [];
        if ($isCheck == 1 || $isCheck == TRUE) {
            if ($model->hasAttribute('wef_date')) {
                $where = ['wef_date' => $wef_date];
            } else {
                $where = '0=1';
            }
        }
        $condition = '';
        if (isset($periodic_applicability) && ($periodic_applicability == TRUE)) {
            $from_date = !empty(Yii::$app->request->post('from_date')) ? date('Y-m-d', strtotime(Yii::$app->request->post('from_date'))) : '';
            $to_date = !empty(Yii::$app->request->post('to_date')) ? date('Y-m-d', strtotime(Yii::$app->request->post('to_date'))) : '';
            if (!empty($from_date) && !empty($to_date)) {
                $condition = '((\'' . $from_date . '\' between from_date  and to_date) OR (\'' . $to_date . '\' between from_date  and to_date) OR (from_date between \'' . $from_date . '\' and  \'' . $to_date . '\') OR (to_date between \'' . $from_date . '\' and \'' . $to_date . '\'))';
            }
        }
        $modelQuery = $model->find()->select(['applicable_code'])->where(['applicable_for' => $filter]);
        if($check_applicability_with_field_name){
            $modelQuery->andWhere([$field_name => $field_code]);
        }
        $modelQuery->andWhere($where)->andWhere($condition);
        $mccCodes = !empty(Yii::$app->request->post('selected_mcc')) ? json_decode(Yii::$app->request->post('selected_mcc')) : [];
        $bmcCodes = !empty(Yii::$app->request->post('selected_bmc')) ? json_decode(Yii::$app->request->post('selected_bmc')) : [];
        $routeCodes = !empty(Yii::$app->request->post('selected_route')) ? json_decode(Yii::$app->request->post('selected_route')) : [];
        switch (1) {
            case key_exists('routes', $filters):
                $routs = new TblRouteMapping();
                $filter_data['routes'] = $routs->getRoutes($union_code, TRUE);
                break;
            case in_array('plants', $filters):
                break;
            case in_array('mcc', $filters):
                break;
            case in_array($filter, ['PLANT']):
                $plantModel = new TblPlant();
                $filter_data['applicable_code'] = $plantModel->getPlantList($union_code, TRUE, $modelQuery, true);
                break;
            case in_array($filter, ['MCC']):
                $mccModel = new TblMccPlant();
                $filter_data['applicable_code'] = $mccModel->getMccs($union_code, $modelQuery, true);
                break;
            case in_array($filter, ['BMC']):
                $bmcModel = new TblDcsBmc();
                $filter_data['applicable_code'] = $bmcModel->getBmcs($union_code, $modelQuery, true);
                break;
            case in_array($filter, ['DCS']):
                $bmcModel = new TblDcs();
                $filter_data['applicable_code'] = $bmcModel->getUnionDcs($union_code, $modelQuery, true, $mccCodes, $bmcCodes, 'ref_code', $routeCodes);
                break;
            case in_array($filter, ['USER']):
                $userModel = new User();
                if (isset($is_bulk_notification) && ($is_bulk_notification == TRUE)) {
                    $filter_data['applicable_code'] = $userModel->getAppUserLists($login_type, $mccCodes);
                } else {
                    $filter_data['applicable_code'] = $userModel->getAppUserList($login_type);
                }
                break;
//            case in_array($filter, ['VENDOR']):
//                $vendorModel = new TblCustomerMaster();
//                $filter_data['applicable_code'] = $vendorModel->getCustomerWithType($union_code, 'VENDOR', $modelQuery);
//                break;
            default :
                $vendorModel = new TblCustomerMaster();
                $filter_data['applicable_code'] = $vendorModel->getCustomerWithType($union_code, $filter, $modelQuery, true, true, $mccCodes, $bmcCodes, $routeCodes);
        }
        if (key_exists('mcc', $filters)) {
            $mcc = new TblMccPlant();
            $filter_data['mcc'] = $mcc->getMccs($union_code, [], TRUE);
        }
        return Json::encode(['status' => 'success', 'data' => $filter_data]);
    }

    public function actionLoadBmcData() {
        $mccCodes = !empty(Yii::$app->request->post('selected_mcc')) ? Yii::$app->request->post('selected_mcc') : [];
        $unionCode = !empty(Yii::$app->request->post('union_code')) ? Yii::$app->request->post('union_code') : '';
        $bmcModel = new TblDcsBmc();
        $bmcList = $bmcModel->getMccBmcList($unionCode, json_decode($mccCodes));
        return Json::encode(['status' => 'success', 'data' => $bmcList]);
    }

    public function actionLoadRouteData() {
        $mccCodes = !empty(Yii::$app->request->post('selected_mcc')) ? Yii::$app->request->post('selected_mcc') : [];
        $bmcCodes = !empty(Yii::$app->request->post('selected_bmc')) ? Yii::$app->request->post('selected_bmc') : [];
        $unionCode = !empty(Yii::$app->request->post('union_code')) ? Yii::$app->request->post('union_code') : '';
        $routeModel = new TblRouteMapping();
        $routeList = $routeModel->routeFromDestination([], json_decode($mccCodes), json_decode($bmcCodes), false);
        return Json::encode(['status' => 'success', 'data' => $routeList]);
    }

    public function actionLoadBmc() {
        $post = Yii::$app->request->post();
        $className = Yii::$app->path->getModel($post['class_name']);
        $tableName = $className::tableName();
        $unionCode = $post['union_code'] ? $post['union_code'] : '';
        $bmcList = [];
        if (!empty($post['selected_apply_to'])) {
            $selectedApplyTo = json_decode($post['selected_apply_to']);
            $query = (new Query())
                    ->select('B.*')
                    ->from(['B' => 'tbl_bmc'])
                    ->innerJoin(
                            ['ct' => (new Query())
                        ->select(['customer_type', 'union_code'])
                        ->from('tbl_customer_type')
                        ->where(['is_applicability' => 1])
                        ->andWhere(['union_code' => $unionCode])
                        ->andWhere(['in', 'customer_type', $selectedApplyTo])
                            ], 'ct.union_code = B.union_code'
                    )
                    ->leftJoin(
                            ['A' => $tableName], 'B.bmc_code = A.applicable_code AND A.' . $post['field_name'] . ' = \'' . addslashes($post['field_code']) . '\' AND A.applicable_type = ct.customer_type'
                    )
                    ->where(['A.applicable_code' => null]);
            foreach (['Plant' => 'plant_code', 'MCC' => 'mcc_plant_code', 'BMC' => 'bmc_code'] as $sessionKey => $column) {
                if ($value = Yii::$app->session->get($sessionKey)) {
                    $query->andWhere(["B.$column" => explode(',', $value)]);
                }
            }
            $bmcList = $query->all();
            $bmcList = ArrayHelper::map($bmcList, 'bmc_code', function($bmcList) {
                        return ($bmcList['ref_code'] . ' - ') . $bmcList['bmc_name'];
                    });
        }
        return Json::encode(['status' => 'success', 'data' => $bmcList]);
    }

}
