<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_transit_recovery_history".
 *
 * @property integer $id
 * @property integer $transit_recovery_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $wef_date
 * @property integer $payment_cycle_code
 * @property string $from_date
 * @property string $to_date
 * @property string $milk_type_code
 * @property string $rate
 * @property string $plus_rate
 * @property string $ltr_conversion_rate
 * @property string $spilt_day
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblTransitRecoveryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transit_recovery_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['plant_code', 'milk_type_code', 'union_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'transit_recovery_code', 'originating_org_code', 'originating_org_type', 'updated_by', 'created_by', 'operation_type', 'history_created_by', 'payment_cycle_code', 'rate', 'plus_rate', 'ltr_conversion_rate', 'spilt_day', 'originating_type', 'wef_date', 'from_date', 'to_date', 'updated_at', 'created_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'transit_recovery_code' => Yii::t('app', 'Transit Recovery Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'rate' => Yii::t('app', 'Rate'),
            'plus_rate' => Yii::t('app', 'Plus Rate'),
            'ltr_conversion_rate' => Yii::t('app', 'Ltr Conversion Rate'),
            'spilt_day' => Yii::t('app', 'Spilt Day'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
