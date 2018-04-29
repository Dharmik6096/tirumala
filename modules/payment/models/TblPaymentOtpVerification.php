<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_payment_otp_verification".
 *
 * @property integer $id
 * @property integer $union_bank_payment_code
 * @property string $mobile_no
 * @property string $otp_code
 * @property string $status
 * @property integer $attempt
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentOtpVerification extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_otp_verification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_bank_payment_code', 'attempt'], 'integer'],
            [['mobile_no', 'status', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'otp_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_bank_payment_code' => Yii::t('app', 'Union Bank Payment Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'status' => Yii::t('app', 'Status'),
            'attempt' => Yii::t('app', 'Attempt'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
