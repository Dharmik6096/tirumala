<?php

namespace app\modules\installation\controllers;

use Yii;
use app\modules\installation\models\InstallationIdentity;
use app\modules\installation\models\InstallationIdentitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\TblSentbox;
use app\models\TblSyncHistory;

/**
 * InstallationIdentityController implements the CRUD actions for InstallationIdentity model.
 */
class InstallationIdentityController extends \app\controllers\ChildController
{

    public $menu = [ ];
    public $tables = [ ];
    public $fp;
    public $file_name;
    public $enableZip = true;
    public $_path = null;
    public $back_temp_file = 'db_backup_';
    
    /**
     * Lists all InstallationIdentity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstallationIdentitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InstallationIdentity model.
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
     * Creates a new InstallationIdentity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new InstallationIdentity();
        $this->viewFile = 'create';
        $path = Yii::$app->basePath . '/installation-identity/';
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0777);
        }
        
        if ($this->model->load(Yii::$app->request->post())) {
            
            $this->model->id = $this->model->getCode();
            $this->model->is_active=1;
            $this->model->originating_org_id = Yii::$app->session->get('organizations_code');
            $this->model->originating_org_type = Yii::$app->session->get('organizations_type');
            $identity = explode('-', $this->model->identity);
            if($identity[2]=='4' && !empty($this->model->dcs_code)){
                $this->model->organization_code=$this->model->dcs_code;
            }
            Yii::$app->operation->defaults($this->model, INSERT);
            
            $sentBox = new TblSentbox;
            $sentBox = $sentBox->getData();

            $mapList = $this->setSyncHistory($sentBox,$this->model->organization_code,$this->model->organization_type);

            $fileName = $this->model->organization_type.'_'.$this->model->organization_code.'_'. date ( 'Y.m.d_H.i.s' ).'.sql';
            $this->model->db_path= '/installation-identity/'.$fileName;
            $transaction = $this->generalModel->saveTransaction([$this->model],[],$mapList, ['installation identity', 'create']);

            if ($transaction !== FALSE) {
                
                if($transaction!='customRender')
                    \Yii::$app->sql->createSqlFile($fileName);
                
                $this->model->organization_code = $_POST['InstallationIdentity']['organization_code'];
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    private function setSyncHistory($sentBox,$code,$type) {
        $list = [];
        foreach ($sentBox as $row){
            $modelSync = new TblSyncHistory();
            $modelSync->uuid = $row->uuid;
            $modelSync->sync_status='S';
            $modelSync->dest_org_id = $code;
            $modelSync->dest_org_type = $type;
            $modelSync->own_org_id = Yii::$app->session->get('organizations_code');
            $modelSync->own_type = Yii::$app->session->get('organizations_type');
            $modelSync->sync_timestamp = date('Y-m-d');
            array_push($list, $modelSync);
        }
        return $list;
    }
    
    
    public function actionDownload($id){
        
        $model = $this->findModel($id);
        $fileName = explode('/', $model->db_path);
        $file = $fileName[2];
//        print_r($fileName);
//        echo $file;
//        exit;
        if (isset ( $file )) {
                \Yii::$app->sql->download($file);
//                $sqlFile = $this->path . basename ( $file );
//                if (file_exists ( $sqlFile )) {
//                        $request = Yii::$app->getRequest ();
//                        $request->sendFile ( basename ( $sqlFile ), file_get_contents ( $sqlFile ) );
//                }
                return true;
        }
    }

    protected function customRedirect() {
        return $this->redirect(['index']);
    }
    
        /**
     * Updates an existing InstallationIdentity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * Deletes an existing InstallationIdentity model.
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
     * Finds the InstallationIdentity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstallationIdentity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstallationIdentity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
