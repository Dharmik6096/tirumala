<?php

namespace app\modules\setting\models;

use Yii;

/**
 * This is the model class for table "tbl_general_config".
 *
 * @property integer $config_code
 * @property string $module_name
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 */
class TblGeneralConfig extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_general_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_name', 'created_by', 'updated_by', 'deleted_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_code' => Yii::t('app', 'Config Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblGeneralConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblGeneralConfigQuery(get_called_class());
    }
    
    public function getNoOfData() {
        return $this->find()->select('*')->count();
    }
    public function getData() {
        return $this->find()->select('*')->one();
    }
}
