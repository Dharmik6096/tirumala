<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblMilkQualityGrade;
use app\modules\globalmaster\models\TblMilkQualityGradeSearch;
use app\modules\globalmaster\models\TblMilkQualityGradeHistory;
use app\modules\globalmaster\models\TblMilkType;
use app\components\Model;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblQualityParam;

/**
 * TblMilkQualityGradeController implements the CRUD actions for TblMilkQualityGrade model.
 */
class TblMilkQualityGradeController extends \app\controllers\ChildController {
    /**
     * Lists all TblMilkQualityGrade models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkQualityGradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single TblMilkQualityGrade model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        
        $model = $this->findModel($id);
        
        $modelMilkType = new TblQualityParam();
        $milkType = $modelMilkType->getMilkType();
        $modelMilk = $model->createObject($milkType, 'edit');
        
//        echo '<pre>';
//        print_r($modelMilk);
//        exit;
        return $this->render('view', [
                    'model' => $this->findModel($id), 'modelMilk' => $modelMilk,'milkType' => $milkType
        ]);
    }
    /**
     * Creates a new TblMilkQualityGrade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMilkQualityGrade();
        $model->scenario = 'createSingle';
        $modelMilkType = new TblQualityParam();
        $milkType = $modelMilkType->getMilkType();
        $modelMilk = $model->createObject($milkType);
        if ($model->load(Yii::$app->request->post())) {
            $modelAttributesLoaded = Model::createMultiple(TblMilkQualityGrade::classname());
            Model::loadMultiple($modelAttributesLoaded, Yii::$app->request->post());
            
            foreach ($modelAttributesLoaded as $r){
                $r->union_code=$model->union_code;
                $r->animal_type_code = $model->animal_type_code;
            }
            $validate = ArrayHelper::merge(
                        ActiveForm::validateMultiple($modelAttributesLoaded),
                        ActiveForm::validate($model)
                );
//            $validate = ActiveForm::validateMultiple($modelAttributesLoaded);
//            print_r($validate);
//            exit;
            if (!$validate) {
                $modelAttributes = $_POST['TblMilkQualityGrade'];
                $check = 0;
                $validationCheck = 0;
                foreach ($modelMilk as $key => $row) {
                    if (!empty($modelAttributes[$key]['min_rang']) && !empty($modelAttributes[$key]['max_rang'])) {
                        $check = 1;
                        $modelQuality = new TblMilkQualityGrade();
                        $modelQuality->grade_code = $modelQuality->getCode();
                        $modelQuality->addValues($key, $modelQuality, $modelAttributes);
                        $modelQuality->is_active=1;
                        Yii::$app->operation->defaults($modelQuality, INSERT);
                        $er = \yii\widgets\ActiveForm::validate($modelQuality);
                        if ($modelQuality->save()) {
                            $validationCheck = 1;
                            $sentbox = new \app\models\TblSentbox();
                            if ($sentbox->setSentbox($model, INSERT)) {
                                $modelQuality->flg_sentbox_entry = SENTBOX_FLAG;
                                $modelQuality->save();
                            }
                        }
                    }
                }
                
                if ($check == 0 || $validationCheck==0) {
                    $msg = ($check==0)?'Please add at lease one record of milk type range':'Milk Quality Grade has been already created.';
                    $model->addError('milk_type_code', $msg);
                    
                    $modelMilk = $modelAttributesLoaded;
                    return $this->render('create', [
                                'model' => $model, 'milkType' => $milkType, 'modelMilk' => $modelMilk
                    ]);
                }
                Yii::$app->display->message(true, 'Milk Quality Grade', 'create');
                return $this->redirect(['index']);
            }
            $modelMilk = $modelAttributesLoaded;
        }
        return $this->render('create', [
                    'model' => $model, 'milkType' => $milkType, 'modelMilk' => $modelMilk
        ]);
    }
    /**
     * Updates an existing TblMilkQualityGrade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'createSingle';
        $modelMilkType = new TblQualityParam();
        $milkType = $modelMilkType->getMilkType();
        $modelMilk = $model->createObject($milkType, 'edit');
        $model->federation_code = $model->unionCode->federationCode->federation_code;
        if (Yii::$app->request->post()) {
            $modelAttributes = Model::createMultiple(TblMilkQualityGrade::classname());
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());
            $validate = ActiveForm::validateMultiple($modelAttributes);
             
            if (!$validate) {
                $modelAttributes = $_POST['TblMilkQualityGrade'];
                
                foreach ($modelMilk as $key => $row) {
                    if (!empty($modelAttributes[$key]['min_rang']) && !empty($modelAttributes[$key]['max_rang'])) {
                        if (!empty($modelAttributes[$key]['grade_code'])) {
                            $modelQuality = TblMilkQualityGrade::findOne($modelAttributes[$key]['grade_code']);
                            $localHistory = new TblMilkQualityGradeHistory();
                            Yii::$app->operation->history($modelQuality,$localHistory,UPDATE);
                        } else {
                            $modelQuality = new TblMilkQualityGrade();
                        }
                        $modelQuality->addValues($key, $modelQuality, $modelAttributes);
                        Yii::$app->operation->defaults($modelQuality, UPDATE);
                        if ($modelQuality->save()) {
                            $sentbox = new \app\models\TblSentbox();
                            if ($sentbox->setSentbox($model, INSERT)) {
                                $modelQuality->flg_sentbox_entry = SENTBOX_FLAG;
                                $modelQuality->save();
                            }
                        }
                    }
                }
                Yii::$app->display->message(true, 'Milk Quality Grade', 'edit');
                return $this->redirect(['index']);
            }
            $modelMilk = $modelAttributes;
        }
        return $this->render('update', [
                    'model' => $model, 'milkType' => $milkType, 'modelMilk' => $modelMilk
        ]);
    }
    /**
     * Deletes an existing TblMilkQualityGrade model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    /**
     * Finds the TblMilkQualityGrade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkQualityGrade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkQualityGrade::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
