<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblAlertRuleMapping;
use app\modules\sms\models\TblAlertRuleMappingSearch;
use yii\web\NotFoundHttpException;
use app\modules\general\models\TblDepartment;
use app\modules\sms\models\TblAlertRuleMasterSearch;
use app\modules\sms\models\TblAlertRuleMaster;

/**
 * TblAlertRuleMappingController implements the CRUD actions for TblAlertRuleMapping model.
 */
class TblAlertRuleMappingController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblAlertRuleMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAlertRuleMapping model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $this->model = $this->findModel($id);
        $searchModel = new TblAlertRuleMappingSearch();
        $searchModel->rule_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblAlertRuleMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblAlertRuleMapping();
        $department = TblDepartment::find()->select('department_id,department')->where(['is_active' => 1])->all();
        $post_data = Yii::$app->request->post('TblAlertRuleMapping');

        if ($model->load(Yii::$app->request->post())) {

            $saveModel = [];
            $departments_selection = $model->department_id;
            $org_selection = $model->organization_type;
            $error_message = '';
            if (!empty($departments_selection) && !empty($org_selection)) {
                foreach ($departments_selection as $departments) {
                    foreach ($org_selection as $org_types) {
                        $mapping_model = new TblAlertRuleMapping();
                        $mapping_model->department_id = $departments;
                        $mapping_model->union_code = $model['union_code'];
                        $mapping_model->rule_code = $model['rule_code'];
                        $mapping_model->organization_type = $org_types;
                        if ($org_types == 'UNION') {
                            $mapping_model->module_name = 'union';
                            $mapping_model->result_key = 'union_code';
                        } else if ($org_types == 'PLANT') {
                            $mapping_model->module_name = 'plant';
                            $mapping_model->result_key = 'plant_code';
                        } else if ($org_types == 'MCC') {
                            $mapping_model->module_name = 'mccPlant';
                            $mapping_model->result_key = 'mcc_plant_code';
                        } else if ($org_types == 'DCS') {
                            $mapping_model->module_name = 'society';
                            $mapping_model->result_key = 'dcs_code';
                        }
                        if ($mapping_model->validate()) {
                            $saveModel[] = $mapping_model;
                        } else {
                            $errors = $mapping_model->getErrors();
                            foreach ($errors as $key => $err) {
                                $error_message .= implode('<br>', $err);
                            }
                        }
                    }
                }
            }
            if (!empty($error_message)) {
                $model->addError('rule_code', $error_message);
            }
            if (!empty($saveModel)) {
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Alert Rule Mapping', 'create']);
                if ($transaction == 'customRedirect' && empty($error_message)) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'department' => $department,
        ]);
    }

    /**
     * Finds the TblAlertRuleMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAlertRuleMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAlertRuleMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
