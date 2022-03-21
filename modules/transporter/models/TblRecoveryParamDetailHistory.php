<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_recovery_param_detail_history".
 *
 * @property integer $id
 * @property integer $param_detail_code
 * @property string $chilling_cost
 * @property string $incentive_value
 * @property string $wef_date
 * @property integer $is_active
 * @property string $plant_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblRecoveryParamDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_recovery_param_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['param_detail_code', 'is_active'], 'safe'],
            [['chilling_cost', 'incentive_value'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['plant_code', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'param_detail_code' => Yii::t('app', 'Param Detail Code'),
            'chilling_cost' => Yii::t('app', 'Chilling Cost'),
            'incentive_value' => Yii::t('app', 'Incentive Value'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
