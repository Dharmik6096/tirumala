<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\tankermovement\models\TblPaymentHead;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * This is the model class for table "tbl_vehicle_transporter_head_mapping".
 *
 * @property integer $vehicle_transporter_head_mapping_code
 * @property integer $transporter_payment_head_code
 * @property string $vehicle_code
 * @property string $wef_date
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentHeadTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_head_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_head_code', 'payment_head_type', 'applicable_date', 'applicable_code', 'applicable_for', 'amount', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'payment_type'], 'safe'],
            [['payment_type', 'payment_head_code', 'applicable_code', 'applicable_date', 'amount'], 'required'],
            [['amount'], 'number', 'min' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_head_code' => Yii::t('app', 'Payment Head'),
            'payment_head_type' => Yii::t('app', 'Payment Head Type'),
            'applicable_date' => Yii::t('app', 'Date'),
            'applicable_code' => Yii::t('app', 'Party Name'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'amount' => Yii::t('app', 'Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPaymentHeadType() {
        return $this->hasOne(TblPaymentHead::className(), ['payment_head_code' => 'payment_head_code']);
    }

    public function getPartyName() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'applicable_code']);
    }

}
