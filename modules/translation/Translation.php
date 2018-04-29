<?php

namespace app\modules\translation;
use yii\data\ArrayDataProvider;
/**
 * translation module definition class
 */
class Translation extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\translation\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    public static function getLanguages($model,$field,$field_value,$extra_param=''){
        $extra_param = (!empty($extra_param))?', '.$extra_param:'';
       return $model::find()->where([$field=>$field_value])->select('local_name,local_code,language_code'.$extra_param)->orderby('language_code')->all();
    }
    public static function ifExist($language,$data){

        foreach($data as $d){

            if($d->language_code==$language){
                return $d;
            }
        }
        return false;
    }
    
    public static function getExportDataProvider($data){
        
        $provider = new ArrayDataProvider([
                    'allModels' => $data,
        ]);
        
        return $provider;
    }
    
    public static function getContentHeaders($type){
        
        $return = '';
        switch ($type){
            case 'excel':
                $return = [
                        'mime' => 'application/vnd.ms-excel',
                        'extension' => 'xls',
                        'writer' => 'Excel5',
                    ];
                break;
            case 'csv':
                $return = [
                        'mime' => 'application/csv',
                        'extension' => 'csv',
                        'writer' => 'CSV',
                ];
                break;
        }
        
        return $return;
    }
    
    public static function getTranslation($model,$selectFields,$langCode,$fieldName,$fieldValue){
        
        return $model::find()->where([$fieldName=>$fieldValue,'language_code'=>$langCode])->select($selectFields)->orderby('language_code')->one();
    }
}
