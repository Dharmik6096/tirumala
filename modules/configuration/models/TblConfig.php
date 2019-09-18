<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_config".
 *
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property string $config_for
 */
class TblConfig extends \app\models\ChildModel {

    public $union_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_name', 'config_key', 'config_for'], 'string'],
            [['union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_for' => Yii::t('app', 'Application'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function configForList() {
        $data = $this->find()
                ->distinct()
                ->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'config_for', 'config_for');
        return $array;
    }

    public function getConfigData($code) {
        $data = $this->find()
                ->where(['config_for' => $code])
                ->all();
        return $data;
    }

}
