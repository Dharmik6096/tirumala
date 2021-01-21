<?php

namespace app\modules\vsp\controllers;

use app\modules\collection\models\TblCollectionPenaltyRateApplicability;
use app\modules\collection\models\TblCollectionPenaltyType;
use app\modules\vsp\models\TblVspTransitRecovery;
use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\vsp\models\TransitRecovery;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `JasperReports` module
 */
class TransitRecoveryController extends \app\controllers\ChildController {

    private $output = '', $dataProvider = '';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionTransitLossShortage() {
        $model = new TransitRecovery();
        $model->scenario = 'transit-shortage';
        $client_code = \Yii::$app->session->get('eiplCode');
        $clientData = $this->setClientData($client_code);
        $modelData = [];
        $clientRender = isset($clientData['fileToRender']) ? $clientData['fileToRender'] : 'transit_recovery_mmd';
        $clientSp = isset($clientData['spName']) ? $clientData['spName'] : 'sp_process_ts_loss_shortage_calculation';
        $btn_status = 'Generated';
        if (Yii::$app->request->post()) {
            if(Yii::$app->request->post('submitBtn') == 'unlock'){
                $btn_status = 'Unlock';
            }
            if(Yii::$app->request->post('submitBtn') != 'unlock'){
                $update_status = 'Generated';
                if (Yii::$app->request->post('submitBtn') === 'save_lock') {
                    $status = 'Lock';
                }
                else {
                    $status = 'Processed';
                }
                $save_model =[];
                $historyModel = [];
                $post_data = Yii::$app->request->post();
                $transist_model_update_all = new TblVspTransitRecovery();
                $model_filter = $_GET['TransitRecovery'];
                $from_shift = Yii::$app->general->getshift($model_filter['from_shift']);
                $to_shift = Yii::$app->general->getshift($model_filter['to_shift']);
                $model_filter['from_date'] = date('Y-m-d H:i:s', strtotime($model_filter['from_date'] . ' ' . $from_shift));
                $model_filter['to_date'] = date('Y-m-d H:i:s', strtotime($model_filter['to_date'] . ' ' . $to_shift));
                $condition = ['and',
                    ['union_code'=>$model_filter['union_code']],
                    ['plant_code'=>$model_filter['plant_code']],
                    ['mcc_plant_code'=>$model_filter['mcc_plant_code']],
                    ['bmc_code'=>$model_filter['bmc_code']],
                    ['>=','from_date',$model_filter['from_date']],
                    ['<=','to_date',$model_filter['to_date']],
                    ['status' => $update_status],
                    // ['order_on'=>$model_filter['order_on']],
                ];
                $trans = $transist_model_update_all->updateAll(['status' => $status],$condition);
                if(isset($post_data['vsp_transit_recovery_code'])){
                    foreach ($post_data['vsp_transit_recovery_code'] as $key => $value) {
                        $transist_model = new TblVspTransitRecovery();
                        $transist_model = $transist_model->find()->where(['vsp_transit_recovery_code' => $value])->one();
                        if(isset($post_data['ts_loss_responsibility'][$value])){
                            $transist_model->ts_loss_responsibility = $post_data['ts_loss_responsibility'][$value][0];
                        }
                        if(isset($post_data['qty_diff_type'][$value])){
                            $transist_model->qty_diff_type = $post_data['qty_diff_type'][$value][0];
                        }
                        if(isset($post_data['qty_diff_responsibility'][$value])){
                            $transist_model->qty_diff_responsibility = $post_data['qty_diff_responsibility'][$value][0];
                        }
                        if(isset($post_data['shortage_recovery'][$value])){
                            $transist_model->shortage_recovery = $post_data['shortage_recovery'][$value][0];
                        }
                        if(isset($post_data['total_recovery_incharge'][$value])){
                            $transist_model->total_recovery_incharge = $post_data['total_recovery_incharge'][$value][0];
                        }
                        if(isset($post_data['total_recovery_transporter'][$value])){
                            $transist_model->total_recovery_transporter = $post_data['total_recovery_transporter'][$value][0];
                        }
                        $transist_model->status = $status;
                        $save_model[] = $transist_model;
                    }
                }
                // var_dump($trans);die;
                $transaction = $this->generalModel->saveTransaction($save_model, $historyModel, ['vsp transit recovery', 'edit']);
                if ($transaction == 'customRedirect') {
                    // var_dump($trans);die;
                    // $this->redirect(['transit-loss-shortage']);
                            return $this->render($clientRender, [
                                'model' => $model,
                                'modelData' => $modelData,
                                'dataProvider' => $this->dataProvider,
                                'output' => $this->output,
                    ]);
                }
            }
        }
//        $dataProvider = $model->transitshortagesearch(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->queryParams) && $model->validate()) {
            $this->LoadData(Yii::$app->request->queryParams, $clientSp, $btn_status);
        }
        

        if (!empty($this->output)) {
            foreach ($this->output as $d) {
                $models = new TransitRecovery();
                $models->setAttributes($d);
                $modelData[] = $models;
            }
        }
        return $this->render($clientRender, [
                    'model' => $model,
                    'modelData' => $modelData,
                    'dataProvider' => $this->dataProvider,
                    'output' => $this->output,
        ]);
    }

    private function LoadData($model, $sp, $btn_status) {
        $output = [];
        if (!empty($model)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => '',
                'status' => $btn_status,
                'order_on' => '',
            ];
            $sp_params = array_merge($sp_params, $model['TransitRecovery']);
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);
            unset($sp_params['dcs_code']);
            if (!empty($sp_params['bmc_code'])) {
                $execute = false;
                if($btn_status == 'Unlock'){
                    $execute = true;
                }
                $output = \Yii::$app->general->getSpData($sp, $sp_params,$execute);
                if($btn_status === 'Unlock'){
                    $this->redirect(['transit-loss-shortage']);
                }
                $this->output = $output;
            }
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $this->dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
    }

    public function setClientData($clientCode = '') {
        $data = [
            'MILKYMIST' => [
                'fileToRender' => 'transit_recovery_mmd',
                'spName' => 'sp_process_ts_loss_shortage_calculation'
            ]
        ];
        return !empty($data[$clientCode]) ? $data[$clientCode] : [];
    }

    public function actionGetPenaltyType(){
        $union_code = $_POST['union'];
        $penalty_model = new TblCollectionPenaltyType();
        $penalty_model = $penalty_model->find()->where(['union_code' => $union_code])->all();

        $data = ArrayHelper::map($penalty_model, function($penalty_model) {
            return (string) $penalty_model->penalty_type_code;
        }, 'penalty_type');

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!empty($data)){
            return ['status' => 'success', 'res' => $data];   
        }
        return ['status' => 'error', 'res' => []];
    }

    public function actionGetPenaltyRate(){
        $dcs_code = $_POST['dcs_code'];
        $transaction_date = $_POST['transaction_date'];
        $qty_diff_type = $_POST['qty_diff_type'];

        $penalty_rate_applicability = new TblCollectionPenaltyRateApplicability();
        $penalty_rate_applicability = $penalty_rate_applicability->find()->where('cast(wef_date as date) <= \''.$transaction_date.'\'')->andWhere(['applicable_code' => $dcs_code])->andWhere(['penalty_type' => $qty_diff_type])->orderBy('wef_date DESC')->one();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!empty($penalty_rate_applicability)){
            return ['status' => 'success', 'res' => $penalty_rate_applicability->penalty_rate];
        }
        return ['status' => 'error', 'res' => []];
    }

}
