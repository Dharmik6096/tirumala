<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_member_payment_recovery".
 *
 * @property integer $recovery_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $for_member_code
 * @property string $from_member_code
 * @property string $recovery_amount
 * @property integer $payment_cycle_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberPaymentRecovery extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recovery_amount'], 'number'],
            [['payment_cycle_code', 'from_shift', 'to_shift', 'originating_type'], 'integer'],
            [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code', 'dcs_code'], 'string', 'max' => 12],
            [['for_member_code', 'from_member_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'recovery_code' => Yii::t('app', 'Recovery Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'for_member_code' => Yii::t('app', 'For Member Code'),
            'from_member_code' => Yii::t('app', 'From Member Code'),
            'recovery_amount' => Yii::t('app', 'Recovery Amount'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function gerRecovery() {
        return $this->find()->where(['dcs_code' => $this->dcs_code, 'payment_cycle_code' => $this->payment_cycle_code, 'from_member_code' => $this->from_member_code, 'for_member_code' => $this->for_member_code])->one();
    }

}
