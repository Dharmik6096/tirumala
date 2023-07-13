<?php

namespace app\modules\installation\controllers;

use yii\web\Controller;
use app\models\IdentityMaster;
use yii\helpers\Json;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use webvimark\modules\UserManagement\models\User;
use app\models\TblUserOrganizationMapping;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use app\modules\import\ARImportStrategy;
use app\models\TblAddressbook;
use app\models\GeneralModel;
use yii\base\UserException;
use Yii;
/**
 * Default controller for the `installation` module
 */
class DefaultController extends Controller
{
    
    protected $viewFile;
    protected $model;
    protected $generalModel;
    public $roleName = 'NDDB_Officials_Role';
    public $roles = ['FEDERATION'=>'Dairy_Federation_Officials_Role','UNION'=>'Dair_Union_Officials_Role'];

    public function init() {
        parent::init();
        $this->generalModel=new GeneralModel();
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->model = new IdentityMaster();
        $identity = $this->model->getIdentity();
        $this->viewFile = 'index';
        if(!empty($identity)){
            return $this->goHome();
        }
        
        $this->layout = "@app/web/themes/nddb/layouts/installationLayout.php";
        
        Yii::$app->db->createCommand()->truncateTable('auth_assignment')->execute();
        Yii::$app->db->createCommand()->truncateTable('tbl_addressbook')->execute();
        Yii::$app->db->createCommand()->truncateTable('identity_master')->execute();
        Yii::$app->db->createCommand()->truncateTable('tbl_user_organization_mapping')->execute();
        Yii::$app->db->createCommand()->truncateTable('tbl_sentbox')->execute();
        Yii::$app->db->createCommand()->truncateTable('tbl_sync_history')->execute();
        Yii::$app->db->createCommand()->truncateTable('tbl_inbox')->execute();
        
        if ( $this->model->load(\Yii::$app->request->post()) ){
            
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $ary = explode('-', $this->model->identity);
            $this->model->parent_url = $this->model->sync_url;
            $this->model->is_active=1;
            $this->model->is_delete=0;
            $this->model->created_at=date('Y-m-d H:i:s');
            $this->model->flg_sentbox_entry = 'N';
            $this->model->sync_status = 'U';
            $this->model->username = $this->model->organization_code.'#'.$this->model->username;
            $this->model->id = $this->model->organization_code;
            if($this->model->save()){
                //$mapList = [];
   
                $fileName = strtolower($this->model->organization_type);
                $user = new User();
                $user->id = $user->getInstallationCode($this->model->organization_type,$this->model->organization_code,$this->model->parent_code);
                $user->user_code = $user->id;
                $user->user_identity = $this->model->organization_code;
                $user->name = $this->model->username;
                $user->username = $this->model->username;
                $user->password_hash = \Yii::$app->security->generatePasswordHash($this->model->password);
                $user->is_active=1;
                $user->status=1;
                $user->user_type_id=$ary[2];
                $user->portal_type='portal';
                if($user->save()){
                    //$mapList = array_merge($mapList, [$user]);
                    $userOrgMapp = new TblUserOrganizationMapping();
                    $userOrgMapp->id = $userOrgMapp->getInstalltionCode($this->model->organization_code);
                    $userOrgMapp->organization_code = $this->model->organization_code;
                    $userOrgMapp->organization_type = $this->model->organization_type;
                    $userOrgMapp->user_id = $user->id;
                    $userOrgMapp->is_active=1;
                    $userOrgMapp->created_at = date('Y-m-d H:i:s');
                    if($userOrgMapp->save()){
                        //$mapList = array_merge($mapList, [$userOrgMapp]);

                        //$transaction = $this->generalModel->saveTransaction([$this->model],[],$mapList,['Identity', 'create']);
                        //if ($transaction !== FALSE) {
                           User::assignRole($user->id, $this->roles[$this->model->organization_type]);
//                           $this->model->username = $_POST['IdentityMaster']['username'];
                           $this->importCsv($fileName,$this->model->organization_code,$this->model->organization_type);
                           $transaction->commit();
                           return $this->customRedirect();
                        //}
                    }
                }
              }
              $this->model->username = $_POST['IdentityMaster']['username'];
            } catch (UserException $e) {
                    $transaction->rollback();
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => $e->getMessage()]);
                    return false;
            }
        }
        return $this->customRender();
    }
    
    public function importCsv($fileName,$orgCode,$orgType){
        
        $path = \Yii::$app->basePath . '/addressbook/'.$fileName.'.csv';
        
        $data=  \app\modules\import\importData::getLabels('addressbook');
        
        $importer = new CSVImporter();
        $fields=  explode(',', $data['fields']);
        
        foreach($fields as $i =>$d){
            $config_value[$i]['attribute']=$d;
            $config_value[$i]['value']=function($line)use ($i) {
//                return $line[$i]; 
                return isset($line[$i])?$line[$i]:'0'; 
            };
        }
        
        $importer->setData(new CSVReader([
            'filename' => $path,
            'fgetcsvOptions' => [
                'delimiter' => ','
            ]
        ]));
        
        
        $primaryKeys = $importer->import(new ARImportStrategy([
            'className'=> \app\models\TblAddressbook::className(),
             'insert'=>2,
             'configs' =>$config_value,
             'defaultFields'=>[]
            ]));
        TblAddressbook::updateAll(['organization_code'=>$orgCode,'organization_type'=>$orgType]);
        
        return true;
    }

        public function actionParentList(){
        $out = '';
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'][0];
            
            if(!empty($value)){
            
                if($value=='NATIONAL-FEDERATION-2'){
                    $list = ['91'=>'National'];
                }else if($value=='91' || $value=='FEDERATION-UNION-3' || $value=='UNION-DCS-4'){
                    $model = new TblFederations();
                    $list = $model->getActiveFederation();
                }else{
                    $model = new TblUnions(); 
                    $list = $model->getUnions($value);
                }
                
                foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
                return;
            }
        }
        return Json::encode(['output' => '']);
    }
    
    public function actionDcsList(){
        $out = '';
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'][0];
            
            $model = new TblDcs();
            $list = $model->getDcsList($value);
            
            foreach ($list as $key => $r) {
                    $out[] = array('id' => $key,
                        'name' => $r);
                }
                return Json::encode(['output' => $out]);
                return;
        }
        return Json::encode(['output' => '']);
    }
    
    protected function customRedirect(){
        return $this->goHome();
    }
    protected function customRender(){
        return $this->render($this->viewFile, ['model' => $this->model]);
    }
}
