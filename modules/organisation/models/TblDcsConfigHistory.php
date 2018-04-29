<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_config_history".
 *
 * @property integer $id
 * @property integer $config_id
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $max_weigh
 * @property integer $max_farmer
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 */
class TblDcsConfigHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_id', 'max_farmer', 'is_active'], 'integer'],
            [['dcs_code', 'bmc_code', 'created_by', 'updated_by', 'operation_type'], 'string'],
            [['max_weigh'], 'number'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_id' => Yii::t('app', 'Config ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'max_weigh' => Yii::t('app', 'Max Weigh'),
            'max_farmer' => Yii::t('app', 'Max Farmer'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
