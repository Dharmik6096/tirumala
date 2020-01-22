<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_dpu_incentive_master_history".
 *
 * @property integer $id
 * @property integer $incentive_master_code
 * @property string $dcs_code
 * @property string $m_cutoff_time
 * @property string $e_cutoff_time
 * @property string $m_start_time
 * @property string $e_start_time
 * @property string $m_lock_time
 * @property string $e_lock_time
 * @property string $inc_rate
 * @property string $inc_deduction
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDpuIncentiveMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dpu_incentive_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['incentive_master_code'], 'safe'],
            [['dcs_code', 'm_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'union_code'], 'required'],
            [['dcs_code', 'm_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'union_code', 'inc_rate', 'inc_deduction', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'from_date', 'to_date'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'incentive_master_code' => Yii::t('app', 'Incentive Master Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'm_cutoff_time' => Yii::t('app', 'M Cutoff Time'),
            'e_cutoff_time' => Yii::t('app', 'E Cutoff Time'),
            'm_start_time' => Yii::t('app', 'M Start Time'),
            'e_start_time' => Yii::t('app', 'E Start Time'),
            'm_lock_time' => Yii::t('app', 'M Lock Time'),
            'e_lock_time' => Yii::t('app', 'E Lock Time'),
            'inc_rate' => Yii::t('app', 'Inc Rate'),
            'inc_deduction' => Yii::t('app', 'Inc Deduction'),
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
