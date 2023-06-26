<?php

namespace app\controllers;

use Yii;
use app\models\SystemConfiguration;
use app\models\SystemConfigurationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblUnions;
use app\components\Model;
use yii\widgets\ActiveForm;

/**
 * SystemConfigurationController implements the CRUD actions for SystemConfiguration model.
 */
class SystemConfigurationController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'ghost-access' => [
                'class' => 'app\modules\usermanagement\components\GhostAccessControl',
            ],
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    /* [
                      'allow' => false,
                      'matchCallback' => function($rule, $action) {
                      $identityModel = new \app\models\IdentityMaster();
                      $identity = $identityModel->getIdentity();
                      if ($identity) {
                      if ($identity->organization_code !== Yii::$app->session->get('organizations_code')) {
                      return true;
                      } else
                      return false;
                      }
                      return true;
                      }
                      ], */
                        [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SystemConfiguration models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SystemConfigurationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemConfiguration model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SystemConfiguration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /* public function actionCreate($flag)
      {
      $model = new SystemConfiguration();
      $modelUnion = new TblUnions;
      $model = $model->getRecords($flag);

      $view = ($flag=='village')?'villages':'common';
      if ($model->load(Yii::$app->request->post())) {

      $validate = ActiveForm::validate($model);
      if(!$validate){
      //                        $model->save();
      }
      //                print_r(Yii::$app->request->post());
      //                exit;
      $ids = $_POST['SystemConfiguration']['id'];
      $from = $_POST['SystemConfiguration']['from_value'];
      $to = $_POST['SystemConfiguration']['to_value'];
      $unions = $_POST['SystemConfiguration']['union_id'];
      $village_from = $_POST['SystemConfiguration']['village_from'];
      $village_to = $_POST['SystemConfiguration']['village_to'];
      $organization_id = $_POST['SystemConfiguration']['organization_id'];

      if(!empty($unions)){
      foreach ($unions as $key=>$id){
      if(empty ($id)){
      $systemConfing = new SystemConfiguration;
      $systemConfing->organization_id= $organization_id[$key];
      $systemConfing->organization_type = 2;
      $systemConfing->module_name = 'village';
      self::saveDetail($systemConfing,$village_from[$key], $village_to[$key]);
      }else{
      $systemConfing = SystemConfiguration::findOne($id);
      self::saveDetail($systemConfing,$village_from[$key], $village_to[$key]);
      }
      }
      }
      if(!empty($ids)){
      foreach ($ids as $key=>$id){
      $systemConfing = SystemConfiguration::findOne($id);
      self::saveDetail($systemConfing,$from[$key], $to[$key]);
      }
      }else{
      foreach ($ids as $key=>$id){
      $systemConfing = new SystemConfiguration;
      self::saveDetail($systemConfing,$from[$key], $to[$key]);
      }
      }

      return $this->redirect(['create']);
      //            }
      }
      return $this->render('create', ['flag'=>$flag,'view'=>$view,'model' => $model
      ]);

      } */

    public function actionCreate() {
        $model = new SystemConfiguration();
        $modelUnion = new TblUnions;
        $records = SystemConfiguration::find()->orderBy(['id' => SORT_ASC])->limit(3)->all();
        $unionArray = $modelUnion->getActiveUnions(1);
        $unions = $modelUnion->getActiveUnions();

        if ($model->load(Yii::$app->request->post())) {


            $ids = $_POST['SystemConfiguration']['id'];
            $from = $_POST['SystemConfiguration']['from_value'];
            $to = $_POST['SystemConfiguration']['to_value'];
            $unions = $_POST['SystemConfiguration']['union_id'];
            $village_from = $_POST['SystemConfiguration']['village_from'];
            $village_to = $_POST['SystemConfiguration']['village_to'];
            $organization_id = $_POST['SystemConfiguration']['organization_id'];

            if (!empty($unions)) {
                foreach ($unions as $key => $id) {
                    if (empty($id)) {
                        $systemConfing = new SystemConfiguration;
                        $systemConfing->organization_id = $organization_id[$key];
                        $systemConfing->organization_type = 2;
                        $systemConfing->module_name = 'village';
                        self::saveDetail($systemConfing, $village_from[$key], $village_to[$key]);
                    } else {
                        $systemConfing = SystemConfiguration::findOne($id);
                        self::saveDetail($systemConfing, $village_from[$key], $village_to[$key]);
                    }
                }
            }
            if (!empty($ids)) {
                foreach ($ids as $key => $id) {
                    $systemConfing = SystemConfiguration::findOne($id);
                    self::saveDetail($systemConfing, $from[$key], $to[$key]);
                }
            } else {
                foreach ($ids as $key => $id) {
                    $systemConfing = new SystemConfiguration;
                    self::saveDetail($systemConfing, $from[$key], $to[$key]);
                }
            }
            /* if(!empty($model->village_from) && !empty($model->organization_id)){
              $systemConfing = SystemConfiguration::find()->where(['organization_id'=>$model->organization_id])->one();
              if(!$systemConfing){
              $systemConfing = new SystemConfiguration;
              $systemConfing->organization_id = $model->organization_id;
              $systemConfing->organization_type = 2;
              $systemConfing->module_name = 'village';
              }
              self::saveDetail($systemConfing,$model->village_from, $model->village_to);
              } */


            return $this->redirect(['create']);
        } else {
            return $this->render('create', ['model' => $model, 'unions' => $unions, 'records' => $records, 'unionArray' => $unionArray
            ]);
        }
    }

    public static function saveDetail($model, $from, $to) {
        $model->from_value = $from;
        $model->to_value = $to;
        $model->save(false);
    }

    public function actionVillagesConfig($flag) {

        $model = new SystemConfiguration();
        $modelUnion = new TblUnions;
        $model = $model->getRecords($flag);

        $unions = $modelUnion->getActiveUnions();

        if ($model->load(Yii::$app->request->post())) {

            $modelAttributes = Model::createMultiple(SystemConfiguration::classname());
            Model::loadMultiple($modelAttributes, Yii::$app->request->post());

            $validate = ActiveForm::validateMultiple($modelAttributes);

//                echo '<pre>';
//                print_r($validate);
//                exit;
            if (!$validate) {
                foreach ($_POST['SystemConfiguration'] as $post) {
                    if (empty($post['id'])) {
                        $systemConfing = new SystemConfiguration;
                        $systemConfing->organization_id = $post['organization_id'];
                        $systemConfing->organization_type = 2;
                        $systemConfing->module_name = 'village';
                        self::saveDetail($systemConfing, $post['village_from'], $post['village_to']);
                    } else {
                        $systemConfing = SystemConfiguration::findOne($post['id']);
                        self::saveDetail($systemConfing, $post['village_from'], $post['village_to']);
                    }
                }
            }
        }
        return $this->render('create', ['flag' => $flag, 'view' => 'villages', 'model' => $model, 'unions' => $unions
        ]);
    }

    /**
     * Updates an existing SystemConfiguration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SystemConfiguration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemConfiguration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemConfiguration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SystemConfiguration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetVillageLimit($id) {

        $record = SystemConfiguration::find()->where(['organization_id' => $id])->select('id,from_value,to_value')->one();
        $from = '';
        $to = '';
        $ids = '';
        if ($record) {
            $from = $record->from_value;
            $to = $record->to_value;
            $ids = $record->id;
        }
        $value = ['from' => $from, 'to' => $to, 'id' => $ids];

        echo \yii\helpers\Json::encode($value);
    }

}
