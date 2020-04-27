<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_member_outstanding".
 *
 * @property integer $member_outstanding_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $transaction_date
 * @property integer $payment_cycle_code
 * @property string $hold_amount
 * @property string $due_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberOutstanding extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_outstanding';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['transaction_date', 'created_at', 'updated_at'], 'safe'],
                [['payment_cycle_code', 'originating_type'], 'safe'],
                [['hold_amount', 'due_amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_outstanding_code' => Yii::t('app', 'Member Outstanding Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'due_amount' => Yii::t('app', 'Due Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getRecord() {
        return $this->find()
                        ->where(['member_code' => $this->member_code])
                        ->one();
    }

}
