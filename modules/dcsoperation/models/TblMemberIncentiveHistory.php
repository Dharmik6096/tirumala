<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_incentive_history".
 *
 * @property integer $id
 * @property integer $member_incentive_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $member_vendor_code
 * @property string $from_date
 * @property string $to_date
 * @property string $total_qty
 * @property integer $pouring_days
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $milk_amount
 * @property string $bonus_criteria
 * @property string $incentive_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMemberIncentiveHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_incentive_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'total_qty', 'avg_fat', 'avg_snf', 'milk_amount', 'incentive_amount', 'member_incentive_code', 'pouring_days', 'originating_type', 'from_date', 'to_date', 'created_at', 'updated_at', 'bonus_criteria', 'operation_type', 'member_code', 'member_vendor_code', 'history_created_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_incentive_code' => Yii::t('app', 'Member Incentive Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_vendor_code' => Yii::t('app', 'Member Vendor Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'pouring_days' => Yii::t('app', 'Pouring Days'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'milk_amount' => Yii::t('app', 'Milk Amount'),
            'bonus_criteria' => Yii::t('app', 'Bonus Criteria'),
            'incentive_amount' => Yii::t('app', 'Incentive Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
