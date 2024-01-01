<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblTankerRate;
use app\modules\tankermovement\models\TblTankerRateDetails;
use app\modules\tankermovement\models\TblTankerRateDetailsSearch;
use app\modules\tankermovement\models\TblFormulaMaster;
use app\modules\tankermovement\models\TblTankerRateBased;
use app\modules\tankermovement\models\TblQualityParam;
use stdClass;
use app\components\Model;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\tankermovement\models\TblTankerRateDetailsHistory;
use PHPExcel;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\tankermovement\models\TblTankerRateBasedHistory;

/**
 * TblTankerRateDetailsController implements the CRUD actions for TblTankerRateDetails model.
 */
class TblTankerRateDetailsController extends \app\controllers\ChildController {

    public $purchaseModel;
    public $method;
    public $rate_type;
    public $jsonEncoded;

    /**
     * Lists all TblTankerRateDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTankerRateDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTankerRateDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function savePurchaseDetail($modelNew, $attributes, $prCode) {
        $modelNew->tanker_rate_code = $prCode;
        $modelNew->attributes = $attributes;
        $modelNew->is_active = 1;
        Yii::$app->operation->defaults($modelNew, INSERT);
        $error = ActiveForm::validate($modelNew);
        $modelNew->save();
    }

    public function customRedirect() {
//        if ($this->method == 1)
        return $this->redirect(['create-rate', 'id' => $this->purchaseModel->tanker_rate_code, 'method' => $this->method]);
//        else
//            return $this->redirect(['generate-chart', 'id' => $this->purchaseModel->tanker_rate_code]);
    }

    public function customRender() {
//        $qualityModel = new TblQualityParam();
        return $this->render('create', [
                    'method' => $this->method,
                    'purchaseBasedModel' => $this->model, 'jsonEncoded' => $this->jsonEncoded]);
    }

    public function actionCreateRate($id, $method) {

        $this->model = new TblTankerRateBased();
        $this->method = $method;
        if (Yii::$app->request->get('id') != -1) {
            $tankerRate = new TblTankerRate();
            $this->purchaseModel = $tankerRate->getRecord(Yii::$app->request->get('id'));
            $re = ['rate_method' => $this->purchaseModel->rate_gen_method_code, 'wef_date' => $this->purchaseModel->wef_date, 'description' => $this->purchaseModel->description, 'shift_code' => $this->purchaseModel->shift_code, 'union_code' => $this->purchaseModel->union_code];
            $this->jsonEncoded = Json::encode($re);
        }
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblTankerRateBased'])) {
            $this->model = Yii::$app->request->post()['TblTankerRateBased'];

            $main_model = [];
            if (Yii::$app->request->get('id') == -1) {
                $this->jsonEncoded = $this->model['purchase_rate'];
                $jsonData = Json::decode($this->model['purchase_rate']);
                $this->purchaseModel = new TblTankerRate();
                $this->purchaseModel->addTankerRate($jsonData);
                $main_model[] = $this->purchaseModel;
            } else {
                $this->purchaseModel = TblTankerRate::findOne(Yii::$app->request->get('id'));
                // $this->purchaseModel->flg_sentbox_entry = 'E';
                // $main_model[] = $this->purchaseModel;
            }
            $model = new TblTankerRateBased();
            $model->tanker_rate_code = $this->purchaseModel->tanker_rate_code;
            $model->rate_based_code = $model->getCode() + 1;
            $model->milk_quality_type_code = $this->model['milk_quality_type_code'];
            $model->milk_type_code = $this->model['milk_type_code'];
            $model->rate_type_code = $this->model['rate_type_code'];
            $model->std_fat = $this->model['std_fat'];
            $model->std_snf = $this->model['std_snf'];
            $model->base_rate = $this->model['base_rate'];
            if ($model->rate_type_code == '1' && isset($this->model['fat_ratio']) && isset($this->model['snf_ratio']) && isset($this->model['fat_rate']) && isset($this->model['snf_rate'])) {
                $model->fat_ratio = $this->model['fat_ratio'];
                $model->fat_rate = $this->model['fat_rate'];
                $model->snf_ratio = $this->model['snf_ratio'];
                $model->snf_rate = $this->model['snf_rate'];
            } else if ($model->rate_type_code == '2' && isset($this->model['qty_rate'])) {
                $model->qty_rate = $this->model['qty_rate'];
            }
            if ($model->validate()) {
                $main_model [] = $model;
                if ($method == 2) {
                    //TblTankerRateBased::deleteAll(['tanker_rate_code' => Yii ::$app->request->get('id'), 'milk_type_code' => $modelAttributesLoaded[0]['milk_type_code']]);
                    $data = TblTankerRateBased::find()->where(['tanker_rate_code' => Yii ::$app->request->get('id'), 'milk_type_code' => $modelAttributesLoaded[0]['milk_type_code']])->all();
                    for ($i = 0; $i < count($data); $i++) {
                        $detailHistory = new TblTankerRateBasedHistory();
                        Yii::$app->operation->history($data[$i], $detailHistory, DELETE);
                        $detailHistory->save();
                        $data[$i]->delete();
                    }
                }
                $transaction = $this->generalModel->saveTransaction($main_model, ['Data Successfully Added', 'info']);

                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
            $this->model = $model;
        }

        return $this->customRender();
    }

    private function saveBaseData($model, $purchaseModel) {
        $milkQuality = new TblMilkQualityType();
        $model->tanker_rate_code = $purchaseModel['tanker_rate_code'];
        $model->rate_based_code = $model->getCode() + $key;
        $model->milk_quality_type_code = array_search('good', array_map('strtolower', $milkQuality->getActiveQualityType()));

        return $model;
    }

    public function actionGenerateChart($id) {

        $model = new TblTankerRateBased();
        $model->tanker_rate_code = $id;
        $purchaseRate = TblTankerRate::findOne($id);
        $valid = TRUE;
        $message = '';
        $milk_type = $model->find()->select(['milk_type_code', 'rate_type_code', 'tanker_rate_code'])->distinct()->where(['tanker_rate_code' => $id])->all();
        if ($purchaseRate->rate_gen_method_code == 1) {
            $validate = $model->ValidateManualRange($milk_type);
            $valid = $validate[0];
            $message = $validate[1];
        }
        if ($valid) {
            //$purchaseRate->flg_sentbox_entry = 'N';
            $purchaseRate->save();
            foreach ($milk_type as $m) {
                $purchaseDetail = new TblTankerRateDetails();
                if ($purchaseRate->rate_gen_method_code == 1) {
                    $purchaseDetail->calculateManualRate($m->tanker_rate_code, $m->milk_type_code, $m->rateType->rate_type);
                } else {
                    $model->setRateSession();
                    $model->setRateRangeSession($id, $m->milk_type_code);
                    Yii::$app->session->set('rate_method', $purchaseRate->rate_gen_method_code);
                    Yii::$app->session->set('milk_type_code', $m->milk_type_code);
                    Yii::$app->session->set('rate_type_code', $m->rate_type_code);
                    $array = ['start1', 'end1', 'rate1', 'start2', 'end2', 'rate2', 'formula', 'rate_method', 'rate_type_code', 'milk_quality_type_code', 'milk_type_code'];
                    $object = new StdClass;
                    foreach ($array as $a) {
                        $object->{$a} = Yii::$app->session->get($a);
                    }
                    $object->tanker_rate_code = $id;
                    $purchaseDetail->calulateRate($object);
                    foreach ($array as $a) {
                        Yii::$app->session->remove($a);
                    }
                }
            }
        } else {
            return $this->redirect(['create-rate', 'id' => $purchaseRate->tanker_rate_code, 'method' => $purchaseRate->rate_gen_method_code]);
        }
        return $this->redirect(['rate-chart', 'id' => $id, 'milk_type' => $milk_type[0]->milk_type_code]);
    }

    public function actionRateChart($id, $milk_type, $milk_quality) {
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post('TblTankerRateDetails');
            $rate_class = empty($data['rate_class']) ? '0' : $data['rate_class'];
            return $this->redirect(['rate-chart', 'id' => $data['tanker_rate_code'], 'milk_type' => $data['milk_type_code'], 'milk_quality' => $data['milk_quality_type_code']]);
        }
        $purchaseDetail = new TblTankerRateDetails();
        $purchaseDetail->milk_type_code = $milk_type;
        $purchaseDetail->milk_quality_type_code = $milk_quality;
        $purchaseDetail->tanker_rate_code = $id;
        $fat = $purchaseDetail->find()->select(['fat', 'rate_type_code'])->where(['tanker_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->distinct()->orderBy('fat')->all();
        $snf = $purchaseDetail->find()->select(['snf'])->where(['tanker_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->distinct()->orderBy('snf')->all();
        $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf', 'code'])->where(['tanker_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();
//        var_dump($milk_type);

        $class = '';
//        if ($purchaseDetail->purchaseRateCode->rate_gen_method_code == 2 && (strtoupper($purchaseDetail->purchaseRateCode->originating_org_type) == 'UNION')) {
//            $class = 'edit';
//        }

        return $this->render('rate_chart', [
                    'fat' => $fat,
                    'snf' => $snf,
                    'rate' => $rate,
                    'model' => $purchaseDetail,
                    'class' => $class
        ]);
    }

    protected function findModel($id) {
        if (($model = TblTankerRateDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModels($id) {
        if (($model = TblTankerRateBased::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDelete() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = $this->findModels(Yii::$app->request->post('id'));
        $historyModel = new TblTankerRateBasedHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Tanker Rate', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['create-rate', 'id' => $this->model->tanker_rate_code, 'method' => $this->model->purchaseRateCode->rate_gen_method_code]);
        }
    }
}
