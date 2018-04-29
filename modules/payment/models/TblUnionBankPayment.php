<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_union_bank_payment".
 *
 * @property integer $union_bank_payment_code
 * @property string $union_code
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property string $file_path
 * @property string $server_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblUnionBankPayment extends \yii\db\ActiveRecord {

    public $dcs_code;
    public $otp_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_union_bank_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_bank_payment_code'], 'required'],
            [['union_bank_payment_code'], 'integer'],
            [['union_code', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'file_path', 'server_type', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'mobile_no', 'ftp_type', 'ftp_server', 'ftp_username', 'ftp_password', 'ftp_port', 'reverse_ftp_path', 'reverse_server_path', 'compare_file_name','bank_email','bank_mobile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'union_bank_payment_code' => Yii::t('app', 'Union Bank Payment Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'file_path' => Yii::t('app', 'File Path'),
            'server_type' => Yii::t('app', 'Server Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getRecord() {
        return $this->find()->where(['union_code' => $this->union_code])->one();
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
