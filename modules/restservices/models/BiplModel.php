<?php

namespace app\modules\restservices\models;

use Yii;
use app\modules\bipl\models\BiplCalibration;
use app\modules\bipl\models\BiplChangeAcknowledgement;
use app\modules\bipl\models\BiplCleaning;
use app\modules\bipl\models\BiplCollection;
use app\modules\bipl\models\BiplDispatch;
use ReflectionClass;
use app\models\GeneralModel;
use app\modules\general\models\TblSocietyVendor;
use DateTime;
use app\modules\dcsoperation\models\TblMember;

class BiplModel {

    private $data, $model, $username = "BIPL", $password = "@BiPl!2017",$path='C:\BIPLFTP\ErrorLogs';

    function __construct() {
        set_error_handler(array($this, 'handleError'));
    }

    public function handleError($code, $message, $file, $line) {
        return;
    }

    public function manipulation($data) {
        $this->data = $data;
        switch (1)
            {
                case empty($this->data['cp_code']):
                    return $this->responseData(0,'cp_code');
                case empty($this->data['svc']):
                    return $this->responseData(0,'nosvc');
                case empty($this->data['usr']):
                    return $this->responseData(0,'usr');
                 case empty($this->data['pswd']):
                     return $this->responseData(0,'pswd');
                default:break;
            }
        if ($this->data['usr'] == $this->username && $this->data['pswd'] == $this->password) {
            switch (trim($data['svc'])) {
                case 'save_milk_collection':return $this->saveCollection();
                case 'save_dispatch': return $this->saveDispatch();
                case 'save_analyzer_cleaning_logs':return $this->saveCleaning();
                case 'save_analyzer_calibration_logs':return $this->saveCalibration();
                case 'save_analyzer_rate_logs':return $this->saveAcknoledgement();
                case 'save_analyzer_vendor_logs':return $this->saveAcknoledgement();
                default:return $this->responseData(0,'svc');
            }
            
        }
        else{
             return $this->responseData(0,'noauth');
        }
        
    }

    private function saveCollection() {
        $this->model = new BiplCollection();
        return $this->saveData();
    }

    private function saveDispatch() {
        $this->model = new BiplDispatch();
        return $this->saveData();
    }

    private function saveCleaning() {
        $this->model = new BiplCleaning();
        return $this->saveData();
    }

    private function saveCalibration() {
        $this->model = new BiplCalibration();
        return $this->saveData();
    }

    private function saveAcknoledgement() {
        $this->model = new BiplChangeAcknowledgement();
        return $this->saveData();
    }

    private function saveData() {
        $generalModel = new GeneralModel();
        $saveData = [];
        $reponse = 0;
        foreach ($this->data['data'] as $value) {
            $tmp = new ReflectionClass($this->model->className());
            $saveModel = $tmp->newInstanceArgs();
            $saveModel->attributes = $this->data;
            $saveModel->attributes = $value;
            $saveModel->scenario='checkDate';
            $schema = $saveModel->getTableSchema();
            $valid[]=$saveModel->validate();
            if($saveModel->validate()!=FALSE){
                foreach ($saveModel->attributes as $key => $a) {
                    $type = $schema->columns[$key]->type;
                    if ($type == 'date' && $a != '') {
                        $dt = new \DateTime($a);
                        $a = $dt->format('Y-m-d\TH:i:s.u');
                        $saveModel->$key = $a;
                        $saveModel->scenario='default';
                    }
                    if($key=='local_code'){
                        $saveModel->$key=str_pad($saveModel->$key,4,'0',STR_PAD_LEFT);
                    }
                }
            }
            $saveData[] = $saveModel;
            
        }
        if(in_array(FALSE, $valid))
        {
            
            $dir=$this->checkDirectory($this->path);
            if($dir)
            {
                $logs=[];
                foreach($saveData as $smodel)
                {
                    $logs['data'][]=$smodel->getAttributes();
                    $logs['errors'][]=$smodel->getErrors();
                }
                $text=  json_encode($logs);
                $this->createCpLogFile($this->path,$text,$this->data['cp_code']);  
            }
            $reponse = 0;
        }
        else
        {
            $transaction = $generalModel->saveTransaction($saveData, ['bipl services', 'create']);
            if ($transaction === FALSE) {
                $reponse = 0;
                $dir=$this->checkDirectory($this->path);
                if($dir)
                {
                    $text=  'Error ocuured while saving data!!';
                    $this->createCpLogFile($this->path,$text,$this->data['cp_code']);  
                }
            
            } else {
                $reponse = 1;
            }
        }
        return $this->responseData($reponse,$fileName);
    }

    private function responseData($status,$error='') {
        $message = [
            1 => 'Successfully Saved!',
            0 => 'Unable to save!',
        ];
        if(!empty($error) && $status==0)
        {
            switch ($error)
            {
                case 'cp_code':
                    $txt='cp_code can not be blank';
                    break;
                case 'nosvc':
                    $txt='svc can not be blank';
                    break;
                case 'svc':
                    $txt='invalid svc';
                    break;
                case 'usr':
                    $txt='username can not be blank';
                    break;
                case 'pswd':
                    $txt='password can not be blank';
                    break;
                case 'noauth':
                    $txt='username or password incorrect';
                    break;
                default :
                    $txt='unknown error';
            }
            $dir=$this->checkDirectory($this->path);
            if($dir)
            {
                $this->createGenLogFile($this->path,$txt);  
            }
            
        }
        return ["status" => $status, "msg" => $message[$status]];
    }
    
    public function findCensus($model,$attribute,$params)
    {
        if(!empty($model->$attribute))
        {
            $code=  TblSocietyVendor::find()->where(['dcs_code'=>$model->$attribute,'vendor_code'=>'BIPL'])->one();
            if(empty($code))
            {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->$attribute."'". ' not found.'));
                return false;
            }
        }
       
    }
    
    public function validateMilkType($model,$attribute,$params)
    {
        if(!empty($model->$attribute))
        {
			
            if(!in_array(strtolower($model->$attribute), ['cow', 'buffalo', 'mix', 'mixed', 'buf']))
            {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) .' can only have values from: Cow, Buffalo, Mix, Mixed'));
                return false;
            }
        }
    }
    
    public function validateLocalCode($model,$attribute,$params)
    {
        if(!empty($model->$attribute))
        {
            $mcode=str_pad($model->$attribute,4,'0',STR_PAD_LEFT);
            $code= TblMember::find()->where(['dcs_code'=>$model->census_code,'member_code'=>$model->census_code.$mcode])->one();
            if(empty($code))
            {
                $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) ." '".$model->$attribute."'". ' not found.'));
                return false;
            }
        }
    }
    
    protected function checkDirectory($path)
    {
        if (file_exists($path)) {
            if (!is_dir($path)) { //if file is already present, but it's not a dir
                if(mkdir($path, '0755', true)==false)
                {
                   die('Failed to create folders...'.$path);
                   return false;
                }
            }
        } else { //no file exists with this name
           if(mkdir($path, '0755',true)==false)
                {
                    die('Failed to create folders...'.$path);
                    return false;
                }
        }
        return true;
    }
    
    protected function createCpLogFile($path,$text,$cp_code)
    {
        $path=$path.'\\'.$cp_code;
        $dir=  $this->checkDirectory($path);
        if($dir)
        {
            $timestamp=date('d-m-Y-H-i-s');
            $fileName=$path."\\".$timestamp.'.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }
    protected function createGenLogFile($path,$text)
    {
        $path=$path.'\general';
        $dir=  $this->checkDirectory($path);
        if($dir)
        {
            $timestamp=date('d-m-Y-H-i-s');
            $fileName=$path."\\".$timestamp.'.txt';
            $logfile = fopen($fileName, "w") or die("Unable to open file!");
            fwrite($logfile, $text);
            fclose($logfile);
        }
        return;
    }

}
