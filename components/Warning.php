<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;

/*
 * Description: This widget used to display header dynamically
 * By: Dhara
 * Date: 3-5-2016
 */

class Warning extends Component{

    public $msg;
    public $alert_type;
    public $timeout;

    public function hiddenfields($nameWarning,$codeWarning){
        if($nameWarning!=='')
         echo Html::hiddenInput('warning',$nameWarning,['id'=>'warning']);
        if($codeWarning!=='')
         echo Html::hiddenInput('code_warning',$codeWarning,['id'=>'code_warning']);
    }

    public function unique($model,$field,$value,$msg=''){

        $primaryKey = $model->tableSchema->primaryKey[0];
        $values = $model->find()->where([$field=> ucwords($value),'is_active'=>1])->andWhere(['<>',$primaryKey, $model->$primaryKey])->count();
        if($values!=0){

            $modleName = StringHelper::basename(get_class($model));
            $field = strtolower($modleName).'-'.$field;
            $value = !empty($msg)?$msg:$value;
            $message = \Yii::t('app', $value." has been already taken. Are you sure you want to continue?");
             Yii::$app->getSession()->setFlash('success', [
                            'type' => 'confirm',
                            'field'=>$field,
                            'hidden_field'=>'warning',
                            'message' => $message,
                        ]);
             return 0;
        }
       return 1;
    }
    
    public function unique_member($model,$fields,$values,$msg=''){

        $primaryKey = $model->tableSchema->primaryKey[0];
        $value = $model->find()->where([$fields[0]=> ucwords($values[0]),$fields[1]=> ucwords($values[1]),$fields[2]=> ucwords($values[2]),'is_active'=>1])->andWhere(['<>',$primaryKey, $model->$primaryKey])->count();

        if($value!=0){

            $modleName = StringHelper::basename(get_class($model));
            $field = strtolower($modleName).'-'.$fields[0];
            $value = !empty($msg)?$msg:$values[0];
            $message = \Yii::t('app', $value." has been already taken. Are you sure you want to continue?");
             Yii::$app->getSession()->setFlash('success', [
                            'type' => 'confirm',
                            'field'=>$field,
                            'hidden_field'=>'warning',
                            'message' => $message,
                        ]);
             return 0;
        }
       return 1;
    }

    public function codeWarning($model,$from,$to,$value,$field){

        if($value>=$from && $value<=$to){

            $modleName = StringHelper::basename(get_class($model));
            $field = strtolower($modleName).'-'.$field;
            $message = Yii::t('app', "Code you have entered does fall in custom code range of _____ master, are you sure you want to save this record?");
             Yii::$app->getSession()->setFlash('success', [
                            'type' => 'confirm',
                            'field'=>$field,
                            'hidden_field'=>'code_warning',
                            'message' => $message,
                        ]);
             return 0;
        }
        return 1;
    }
}
?>
