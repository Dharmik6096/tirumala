<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\configuration\models\TblMilkQualityParamRangeSearch;
use yii\web\NotFoundHttpException;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\configuration\models\TblMilkQualityParamRangeHistory;

/**
 * TblMilkQualityParamRangeController implements the CRUD actions for TblMilkQualityParamRange model.
 */
class TblMilkQualityParamRangeController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkQualityParamRange models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkQualityParamRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $animal_model = new TblAnimalType();
        $animalDetail = $animal_model->getRecords();
        $model = new TblMilkQualityParamRange();
        $searchModel = new TblMilkQualityParamRangeSearch();
        $searchModel->scenario = 'qualityRange';

        $dataProvider = $searchModel->searchData(Yii::$app->request->queryParams);
        $queryParams = Yii::$app->request->queryParams['TblMilkQualityParamRangeSearch'] ?? [];
        $existingRecords = $this->getExistingRecords($queryParams);

        $type = !empty($existingRecords) ? 'edit' : 'create';
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $h_model = [];
            $is_validate = true;
            if (!empty($queryParams)) {
                $postData = Yii::$app->request->post()['TblMilkQualityParamRange'];
                $processName = $queryParams['process_name'];
                $orgCode = $processName == 'BMC_MILK_DISPATCH' ? $queryParams['bmc_code'] : $queryParams['plant_code'];
                $orgType = $processName == 'BMC_MILK_DISPATCH' ? 'BMC' : 'PLANT';

                foreach ($postData as $data) {
                    if (empty($data['min_fat']) && empty($data['max_fat']) && empty($data['min_snf']) && empty($data['max_snf']) && empty($data['min_clr']) && empty($data['max_clr'])) {
                        continue;
                    }
                    $existingRecord = null;
                    foreach ($existingRecords as $record) {
                        if ($record['animal_type_code'] == $data['animal_type_code']) {
                            $existingRecord = $record;
                            break;
                        }
                    }
                    if ($existingRecord) {
                        $isChanged = false;
                        $milkQualityModel = TblMilkQualityParamRange::findOne($existingRecord['milk_quality_param_range_code']);
                        if ($milkQualityModel) {
                            $historyModel = new TblMilkQualityParamRangeHistory();
                            Yii::$app->operation->history($milkQualityModel, $historyModel, 'UPDATE');
                            $fields = ['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr'];
                            foreach ($fields as $field) {
                                if ($milkQualityModel->$field != $data[$field]) {
                                    $milkQualityModel->$field = $data[$field];
                                    $isChanged = true;
                                }
                            }
                            if ($milkQualityModel->validate() && empty($milkQualityModel->getErrors() && $isChanged)) {
                                $h_model[] = $historyModel;
                                $saveModel[] = $milkQualityModel;
                            } else {
                                $is_validate = false;
                                $model = $milkQualityModel;
                                break;
                            }
                        }
                    } else {
                        $milkQualityModel = new TblMilkQualityParamRange();
                        $milkQualityModel->animal_type_code = $data['animal_type_code'];
                        $milkQualityModel->min_fat = $data['min_fat'];
                        $milkQualityModel->max_fat = $data['max_fat'];
                        $milkQualityModel->min_snf = $data['min_snf'];
                        $milkQualityModel->max_snf = $data['max_snf'];
                        $milkQualityModel->min_clr = $data['min_clr'];
                        $milkQualityModel->max_clr = $data['max_clr'];
                        $milkQualityModel->process_name = $processName;
                        $milkQualityModel->union_code = $queryParams['union_code'];
                        $milkQualityModel->plant_code = $queryParams['plant_code'];
                        $milkQualityModel->mcc_plant_code = !empty($queryParams['mcc_plant_code']) ? $queryParams['mcc_plant_code'] : '';
                        $milkQualityModel->bmc_code = !empty($queryParams['bmc_code']) ? $queryParams['bmc_code'] : '';
                        $milkQualityModel->org_code = $orgCode;
                        $milkQualityModel->org_type = $orgType;
                        if ($milkQualityModel->validate() && empty($milkQualityModel->getErrors())) {
                            $saveModel[] = $milkQualityModel;
                        } else {
                            $is_validate = false;
                            $model = $milkQualityModel;
                            break;
                        }
                    }
                }
            }
        }
        if (!empty($saveModel) && $is_validate) {
            $transaction = $this->generalModel->saveTransaction($saveModel, $h_model, ['Milk Quality Param Range', $type]);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'animalDetail' => $animalDetail,
                    'existingRecords' => $existingRecords,
                    'type' => $type,
        ]);
    }

    private function getExistingRecords($queryParams) {
        $existingRecords = [];
        if (!empty($queryParams)) {
            $org_code = ($queryParams['process_name'] == 'BMC_MILK_DISPATCH') ? $queryParams['bmc_code'] : $queryParams['plant_code'];
            $existingRecords = TblMilkQualityParamRange::find()->where([
                        'process_name' => $queryParams['process_name'],
                        'union_code' => $queryParams['union_code'],
                        'org_code' => $org_code,
                        'org_type' => $queryParams['process_name'] == 'BMC_MILK_DISPATCH' ? 'BMC' : 'PLANT',
                    ])->asArray()->all();
        }
        return $existingRecords;
    }

}
