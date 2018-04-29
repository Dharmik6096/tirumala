<?php

namespace app\modules\customimport\models;

use Yii;
use yii\base\Model;
class ImportForm extends Model {

    public $operation;
    public $module_name;
    public $class_name;
    public $fields;
    public $required;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['module_name','required'],
            [['operation','module_name','class_name','fields','required'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'operation' => Yii::t('app', 'Operation'),
            'module_name' => Yii::t('app', 'Module'),
            'class_name' => Yii::t('app', 'Class'),
            'fields' => Yii::t('app', 'Selected Fields'),
            'required' => Yii::t('app', 'Required Fields'),
        ];
    }        
}
