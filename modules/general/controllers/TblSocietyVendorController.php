<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblSocietyVendor;
use app\modules\general\models\TblSocietyVendorSearch;
use webvimark\modules\UserManagement\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\rbac\DbManager;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use yii\helpers\ArrayHelper;
use webvimark\modules\UserManagement\components\AuthHelper;
use stdClass;
/**
 * TblSocietyVendorController implements the CRUD actions for TblSocietyVendor model.
 */
class TblSocietyVendorController extends \app\controllers\ChildController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblSocietyVendor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $vendors=[];
        $searchModel= new User();
        $authManager = Yii::$app->authManager instanceof DbManager ? Yii::$app->authManager : new DbManager();
        //var_dump($authManager); exit;
        $allRoles = Role::find()->all();
        foreach ($allRoles as $role)
        {
            $currentRoutesAndPermissions = AuthHelper::separateRoutesAndPermissions($authManager->getPermissionsByRole($role->name));
            $currentPermissions = $currentRoutesAndPermissions->permissions;
            if(in_array('Vendor Permission', ArrayHelper::getColumn($currentPermissions, 'name')) && $role->name!='vendor')
            {
                $v=new \stdClass();
                $v->role=$role->name;
                $vendors[]=$v;
            }
            //var_dump($currentPermissions);
        }
        //$users=$searchModel->findByRole(['vendor','BIPL']);       
        //var_dump($users); exit;
        $dataProvider = new ArrayDataProvider([
        'allModels' => $vendors,
        'sort' => [
            'attributes' => ['role'],
        ],
        'pagination' => [
            'pageSize' => 10,
        ],
        ]);
       // var_dump($users); exit;
        
        /*$searchModel = new TblSocietyVendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);*/

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSocietyVendor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSocietyVendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblSocietyVendor();
        $appModel->field_name = 'vendor_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'vendor society';
        $appModel->top_section= false;
        $appModel->select_from_all=true;
        //$appModel->is_union= false;
        $appModel->fields = ['dcs_code' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],
        ];
        return $appModel->vendorApplicability();
    }

    /**
     * Updates an existing TblSocietyVendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->society_vendor_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblSocietyVendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblSocietyVendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSocietyVendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblSocietyVendor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionVendors($id) {
        
        
    }
}
