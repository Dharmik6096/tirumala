<?php

namespace app\modules\vsp\controllers;

use yii\web\Controller;
use yii;
use Jaspersoft\Client\Client;
use app\modules\vsp\models\TransitRecovery;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

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
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'verify' ? 1 : 2) : 1;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];

                $where = [];
                foreach ($codes as $code) {
                    $data = explode('###', $code);
                    $modelUsed = $data[1];
                    if ($modelUsed == 'MEMBER') {
                        $where['member_code'] = $data[0];
                        $existData = TblMember::find()->where($where)->one();
                        $historyModel = new TblMemberHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_verified = $status;
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    } else if ($modelUsed == 'DCS' || $modelUsed == 'CUSTOMER') {
                        $module = $modelUsed == 'DCS' ? 'society' : 'customer';
                        $code = $data[0];
                        $existData = TblBankDetails::find()->where(['module_code' => $code, 'module_name' => $module, 'is_default' => 1, 'is_active' => 1])->one();
                        $historyModel = new TblBankDetailsHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->is_verified = $status;
                        $existData->scenario = 'verification';
                        $saveModel[] = $existData;
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['Master Verified', 'create']);
                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
                }
            }
        }
//        $dataProvider = $model->transitshortagesearch(Yii::$app->request->queryParams);
        $client_code = \Yii::$app->session->get('eiplCode');
        $clientData = $this->setClientData($client_code);

        $clientRender = isset($clientData['fileToRender']) ? $clientData['fileToRender'] : 'transit_recovery_mmd';
        $clientSp = isset($clientData['spName']) ? $clientData['spName'] : 'ts_loass_shortage_mmd';

        if ($model->load(Yii::$app->request->queryParams) && $model->validate()) {
            $this->LoadData(Yii::$app->request->queryParams, $clientSp);
        }
        $modelData = [];

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

    private function LoadData($model, $sp) {
        $output = [];
        if (!empty($model)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'dcs_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => '',
                'order_on' => '',
            ];
            $sp_params = array_merge($sp_params, $model['TransitRecovery']);
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);
            if (!empty($sp_params['bmc_code'])) {
                $output = \Yii::$app->general->getSpData($sp, $sp_params);
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
                'spName' => 'ts_loass_shortage_mmd'
            ]
        ];
        return !empty($data[$clientCode]) ? $data[$clientCode] : [];
    }

}
