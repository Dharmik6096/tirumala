<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetailsSearch;
use app\modules\dcsoperation\models\TblFormulaMaster;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBased;
use app\modules\dcsoperation\models\TblQualityParam;
use app\components\Model;
use stdClass;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetailsHistory;
use PHPExcel;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\dcsoperation\models\TblDcsPurchaseRateBasedHistory;

/**
 * TblDcsPurchaseRateDetailsController implements the CRUD actions for TblDcsPurchaseRateDetails model.
 */
class TblDcsPurchaseRateDetailsController extends \app\controllers\ChildController {

    public $purchaseModel;
    public $method;
    public $rate_type;
    public $jsonEncoded;

    /**
     * Lists all TblDcsPurchaseRateDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsPurchaseRateDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsPurchaseRateDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function savePurchaseDetail($modelNew, $attributes, $prCode) {
        $modelNew->purchase_rate_code = $prCode;
        $modelNew->attributes = $attributes;
        $modelNew->is_active = 1;
        Yii::$app->operation->defaults($modelNew, INSERT);
        $error = ActiveForm::validate($modelNew);
        $modelNew->save();
    }

    public function customRedirect() {
//        if ($this->method == 1)
        return $this->redirect(['create-rate', 'id' => $this->purchaseModel->purchase_rate_code, 'method' => $this->method]);
//        else
//            return $this->redirect(['generate-chart', 'id' => $this->purchaseModel->purchase_rate_code]);
    }

    public function customRender() {
        $qualityModel = new TblQualityParam();
        return $this->render('create', [
                    'method' => $this->method,
                    'purchaseBasedModel' => $this->model, 'jsonEncoded' => $this->jsonEncoded, 'quality_param' => Json::encode($qualityModel->getParams())]);
    }

    public function actionCreateRate($id, $method) {


        $this->model[0] = new TblDcsPurchaseRateBased();
        $this->method = $method;
        $this->model[0]->purchase_rate_code = Yii::$app->request->get('id');


        if (Yii::$app->request->get('id') != -1) {
            $purchaseRate = new TblDcsPurchaseRate();
            $this->purchaseModel = $purchaseRate->getRecord(Yii::$app->request->get('id'));
            $re = ['rate_method' => $this->purchaseModel->rate_gen_method_code, 'wef_date' => $this->purchaseModel->wef_date, 'shift' => $this->purchaseModel->shift_applicability, 'description' => $this->purchaseModel->description, 'shift_id' => $this->purchaseModel->shift_id];

            $this->jsonEncoded = Json::encode($re);
        }

        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblDcsPurchaseRateBased'])) {
            $modelAttributesLoaded = Model::createMultiple(TblDcsPurchaseRateBased::classname(), [], null);
            Model::loadMultiple($modelAttributesLoaded, Yii::$app->request->post());
            $modelAttributesLoaded[0]['purchase_rate_code'] = (string) Yii::$app->request->get('id');

            if ($method == 1) {
                $modelAttributesLoaded[0]->scenario = 'manualForm';
            }

            for ($i = 1; $i < count($modelAttributesLoaded); $i++) {
                if ($method == 1) {
                    $modelAttributesLoaded[$i]->scenario = 'manualForm';
                }
                $modelAttributesLoaded[$i]['purchase_rate_code'] = (string) Yii::$app->request->get('id');
                $modelAttributesLoaded[$i]['milk_type_code'] = $modelAttributesLoaded[0]['milk_type_code'];
                $modelAttributesLoaded[$i]['formula_code'] = $modelAttributesLoaded[0]['formula_code'];
                $modelAttributesLoaded[$i]['formula'] = $modelAttributesLoaded[0]['formula'];
                $modelAttributesLoaded[$i]['rate_type'] = $modelAttributesLoaded[0]['rate_type'];
                $modelAttributesLoaded[$i]['milk_quality_type_code'] = $modelAttributesLoaded[0]['milk_quality_type_code'];
                //  if (isset($modelAttributesLoaded[0]['quality_param_code'])) {
                //  $modelAttributesLoaded[$i]['quality_param_code'] = $modelAttributesLoaded[0]['quality_param_code'];
                //  }
            }

            $validate = ActiveForm::validateMultiple($modelAttributesLoaded);

            if (!$validate) {
                $main_model = [];
                if (Yii::$app->request->get('id') == -1) {
                    $jsonData = Json::decode($_POST['purchase_rate']);
                    $this->purchaseModel = new TblDcsPurchaseRate();
                    $this->purchaseModel->addPurchaseRate($jsonData);
                    $main_model[] = $this->purchaseModel;
                } else {
                    $this->purchaseModel = TblDcsPurchaseRate::findOne(Yii::$app->request->get('id'));
                    $this->purchaseModel->flg_sentbox_entry = 'E';
                    $main_model[] = $this->purchaseModel;
                }

                $list = $this->saveBaseData($modelAttributesLoaded);
                $main_model = array_merge($main_model, $list);
                if ($method == 2) {
                    //TblDcsPurchaseRateBased::deleteAll(['purchase_rate_code' => Yii ::$app->request->get('id'), 'milk_type_code' => $modelAttributesLoaded[0]['milk_type_code']]);
                    $data = TblDcsPurchaseRateBased::find()->where(['purchase_rate_code' => Yii ::$app->request->get('id'), 'milk_type_code' => $modelAttributesLoaded[0]['milk_type_code'], 'milk_quality_type_code' => $modelAttributesLoaded[0]['milk_quality_type_code']])->all();
                    for ($i = 0; $i < count($data); $i++) {
                        $detailHistory = new TblDcsPurchaseRateBasedHistory();
                        Yii::$app->operation->history($data[$i], $detailHistory, DELETE);
                        $detailHistory->save(FALSE);
                        $data[$i]->delete();
                    }
                }

                $transaction = $this->generalModel->saveTransaction($main_model, ['Data Succesfully Added', 'info']);

                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
            $this->model = $modelAttributesLoaded;
        }
        return $this->customRender();
    }

    private function saveBaseData($modelAttributesLoaded) {
        // $milkQuality = new TblMilkQualityType();
        $list = [];
        foreach ($modelAttributesLoaded as $key => $model) {
            $model->purchase_rate_code = $this->purchaseModel->purchase_rate_code;
            $model->rate_based_code = $model->purchase_rate_code . ($model->getCode() + $key);
            // $model->milk_quality_type_code = array_search('good', array_map('strtolower', $milkQuality->getActiveQualityType()));
            $list[] = $model;
        }
        return $list;
    }

    public function actionGenerateChart($id) {

        $model = new TblDcsPurchaseRateBased();
        $model->purchase_rate_code = $id;
        $purchaseRate = TblDcsPurchaseRate::findOne($id);
        $valid = TRUE;
        $message = '';
        $milk_type = $model->find()->select(['milk_type_code', 'rate_type', 'purchase_rate_code', 'milk_quality_type_code'])->distinct()->where(['purchase_rate_code' => $id])->all();
        if ($purchaseRate->rate_gen_method_code == 1) {
            $validate = $model->ValidateManualRange($milk_type);
            $valid = $validate[0];
            $message = $validate[1];
        }
        if ($valid) {
            $purchaseRate->flg_sentbox_entry = 'N';
            $purchaseRate->save();
            foreach ($milk_type as $m) {
                $purchaseDetail = new TblDcsPurchaseRateDetails();
                if ($purchaseRate->rate_gen_method_code == 1) {
                    $purchaseDetail->calculateManualRate($m->purchase_rate_code, $m->milk_type_code, $m->rateType->rate_type, $m->milk_quality_type_code);
                } else {
                    $model->setRateSession();
                    $model->setRateRangeSession($id, $m->milk_type_code);
                    Yii::$app->session->set('rate_method', $purchaseRate->rate_gen_method_code);
                    Yii::$app->session->set('milk_type_code', $m->milk_type_code);
                    Yii::$app->session->set('milk_quality_type_code', $m->milk_quality_type_code);
                    Yii::$app->session->set('rate_type', $m->rate_type);

                    $array = ['start1', 'end1', 'rate1', 'start2', 'end2', 'rate2', 'formula', 'rate_method', 'rate_type', 'milk_quality_type_code', 'milk_type_code'];
                    $object = new StdClass;
                    foreach ($array as $a) {
                        $object->{$a} = Yii::$app->session->get($a);
                    }
                    $object->purchase_rate_code = $id;
                    $purchaseDetail->calulateRate($object);
                    foreach ($array as $a) {
                        Yii::$app->session->remove($a);
                    }
                }
            }
        } else {
            return $this->redirect(['create-rate', 'id' => $purchaseRate->purchase_rate_code, 'method' => $purchaseRate->rate_gen_method_code]);
        }
        return $this->redirect(['rate-chart', 'id' => $id, 'milk_type' => $milk_type[0]->milk_type_code, 'milk_quality' => $milk_type[0]->milk_quality_type_code]);
    }

    public function actionRateChart($id, $milk_type, $milk_quality) {
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post('TblDcsPurchaseRateDetails');
            return $this->redirect(['rate-chart', 'id' => $data['purchase_rate_code'], 'milk_type' => $data['milk_type_code'], 'milk_quality' => $data['milk_quality_type_code']]);
        }
        $purchaseDetail = new TblDcsPurchaseRateDetails();
        $purchaseDetail->milk_type_code = $milk_type;
        $purchaseDetail->purchase_rate_code = $id;
        $purchaseDetail->milk_quality_type_code = $milk_quality;
        $fat = $purchaseDetail->find()->select(['fat', 'rate_type_code'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->distinct()->orderBy('fat')->all();
        $snf = $purchaseDetail->find()->select(['snf'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->distinct()->orderBy('snf')->all();
        $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf', 'code'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();
//        var_dump($milk_type);

        $class = '';
        if ($purchaseDetail->purchaseRateCode->rate_gen_method_code == 2 && (strtoupper($purchaseDetail->purchaseRateCode->originating_org_type) == 'UNION') && $purchaseDetail->purchaseRateCode->flg_sentbox_entry != 'Y') {
            $class = 'edit';
        }

        return $this->render('rate_chart', [
                    'fat' => $fat,
                    'snf' => $snf,
                    'rate' => $rate,
                    'model' => $purchaseDetail,
                    'class' => $class
        ]);
    }

    public function actionUpdateRateChart() {
        if (!empty(Yii::$app->request->post('id')) && !empty(Yii::$app->request->post('rtpl'))) {
            $model = new TblDcsPurchaseRateDetails();
            $model = $model->getRecord(Yii::$app->request->post('id'));
            $validate = $model->rtplValidate(Yii::$app->request->post('rtpl'));
            if ($validate == 'success') {
                $detailHistory = new TblDcsPurchaseRateDetailsHistory();
                Yii::$app->operation->history($model, $detailHistory, UPDATE);
                $detailHistory->save(FALSE);
                $model->rtpl = round(Yii::$app->request->post('rtpl'), 2);
                $model->save();
                $record = ['status' => 'success', 'rtpl' => $model->rtpl];
            } else {
                $record = ['status' => 'error', 'message' => $validate, 'rtpl' => $model->rtpl];
            }
        } else {
            $record = ['status' => 'error', 'message' => 'Record is not updated'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDcsPurchaseRateDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsPurchaseRateDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsPurchaseRateDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetAutoFormulas() {

        $options = '';
        if (!empty(Yii::$app->request->post('milkType')) && !empty(Yii::$app->request->post('rateType'))) {

            $formulaModel = new TblFormulaMaster();
            $formulaModel->milk_type_code = Yii::$app->request->post('milkType');
            $formulaModel->rate_type_code = Yii::$app->request->post('rateType');
            $formulaModel->union_code = Yii::$app->request->post('union_code');
            $formulaArray = $formulaModel->getFormulaes();

            if (!empty($formulaArray)) {
                foreach ($formulaArray as $key => $ary) {
                    $checked = ($key == 0) ? 'checked' : '';
                    $options .= '<label>'
                            . '<input class="" name="formula" value="' . $ary . '" type="radio" ' . $checked . '>
                               ' . $ary . '</label>';
                }
            }
        }
        echo \yii\helpers\Json::encode(['values' => $options]);
    }

    public function actionExportRateChart($id) {
        $objPHPExcel = new PHPExcel();
        $sheetcnt = 0;
        $model = new TblDcsPurchaseRateBased();
        $RateChart = $model->find()->select(['milk_type_code', 'rate_type', 'purchase_rate_code', 'milk_quality_type_code'])->distinct()->where(['purchase_rate_code' => $id])->all();
        foreach ($RateChart as $m) {
            $purchaseDetail = new TblDcsPurchaseRateDetails();
            $milk_type = $m->milk_type_code;
            $quality_type = $m->milk_quality_type_code;
            $fat = $purchaseDetail->find()->select(['fat'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $quality_type])->distinct()->orderBy('fat')->all();
            $snf = $purchaseDetail->find()->select(['snf'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $quality_type])->distinct()->orderBy('snf')->all();
            $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $quality_type])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();
            $rowCount = 1;
            $column = 'A';
            if ($sheetcnt == 0) {
                $sheet = $objPHPExcel->getActiveSheet();
                //$sheet->setTitle($m->milkTypeCode->animal_type_name . '-' . $m->milkQualityTypeCode->milk_quality_type_name);
                $sheet->setTitle($m->milkTypeCode->oldAttributes['animal_type_name'] . '-' . $m->milkQualityTypeCode->oldAttributes['milk_quality_type_name']);
            } else {
                $sheet = $objPHPExcel->createSheet();
                //$sheet->setTitle($m->milkTypeCode->animal_type_name . '-' . $m->milkQualityTypeCode->milk_quality_type_name);
                $sheet->setTitle($m->milkTypeCode->oldAttributes['animal_type_name'] . '-' . $m->milkQualityTypeCode->oldAttributes['milk_quality_type_name']);
            }
            $sheet->setCellValue($column . $rowCount, $m->rateType->rate_type);
            $column++;
            $cnt = 0;
            foreach ($fat as $key => $attr) {
                if ($key == 0) {
                    if (count($snf) == 1 && $snf[0]->snf == NULL) {
                        $sheet->setCellValue($column . $rowCount, 'RTPL');
                    } else {
                        foreach ($snf as $s) {
                            $sheet->setCellValue($column . $rowCount, $s->snf);
                            $column++;
                        }
                    }
                }
                $rowCount++;
                $column = 'A';
                $sheet->setCellValue($column . $rowCount, $attr->fat);
                $column++;
                if (count($snf) == 1 && $snf[0]->snf == NULL) {
                    $sheet->setCellValue($column . $rowCount, $rate[$cnt]->rtpl);
                    $cnt++;
                } else {
                    foreach ($snf as $s) {
                        if (isset($rate[$cnt]) && $s->snf == $rate[$cnt]->snf) {
                            $sheet->setCellValue($column . $rowCount, $rate[$cnt]->rtpl);
                            $cnt++;
                        } else {
                            $sheet->setCellValue($column . $rowCount, '');
                        }
                        $column++;
                    }
                }
            }
            $sheetcnt++;
        }
        $header = [
            'mime' => 'application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel5',
        ];
        $fileName = "RateChart." . $header['extension'];
        header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $header['writer']);
        ob_end_clean();
        $objWriter->save('php://output');
        exit();
    }

    public function actionDelete() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $record = TblDcsPurchaseRateBased::findOne(Yii::$app->request->post('id'));
            $master = $this->deleteBased($record, $master);
            $this->purchaseModel = TblDcsPurchaseRate::findOne($record->purchase_rate_code);
            $this->purchaseModel->flg_sentbox_entry = 'E';
            $master[] = $this->purchaseModel->save();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $return = ['type' => 'error', 'message' => Yii::t('app', 'This record cannot be deleted due to some reference Error.')];
            } else {
                $transaction->commit();
                $return = ['type' => 'success', 'message' => Yii::t('app', 'Records successfuly deleted.')];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $return = ['type' => 'error', 'message' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $return = ['type' => 'error', 'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }
        Yii::$app->getSession()->setFlash('success', $return);

        return $this->redirect(['create-rate', 'id' => $record->purchase_rate_code, 'method' => $record->purchaseRateCode->rate_gen_method_code]);
    }

    private function deleteBased($record, $master) {
        $purchaseratecode = $record->purchase_rate_code;
        $milktype = $record->milk_type_code;
        $qualityparam = $record->quality_param_code;
        $startrange = $record->start_range;
        $endrange = $record->end_range;

        $detailHistory = new TblDcsPurchaseRateBasedHistory();
        Yii::$app->operation->history($record, $detailHistory, DELETE);
        $master[] = $detailHistory->save(FALSE);
        $master[] = $record->delete();
        $data = TblDcsPurchaseRateBased::find()->where('purchase_rate_code="' . $purchaseratecode . '" and milk_type_code="' . $milktype . '" and quality_param_code="' . $qualityparam . '" and fixed_point between "' . $startrange . '" and "' . $endrange . '" ')->all();
        for ($i = 0; $i < count($data); $i++) {
            $record = TblDcsPurchaseRateBased::findOne($data[$i]->rate_based_code);
            $master = $this->deleteBased($record, $master);
        }
        return $master;
    }

}
