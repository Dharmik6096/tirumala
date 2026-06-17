<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_vendor_master_history".
 *
 * @property integer $id
 * @property string $vendor_master_code
 * @property string $vendor_code
 * @property string $vendor_name
 * @property string $pan_no
 * @property string $adhar_no
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVendorMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vendor_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vendor_master_code', 'union_code'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_type'], 'safe'],
            [['vendor_master_code'], 'safe'],
            [['vendor_code'], 'safe'],
            [['vendor_name'], 'safe'],
            [['pan_no', 'aadhaar_no'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'vendor_type', 'is_active', 'local_name', 'ledger_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vendor_master_code' => Yii::t('app', 'Vendor Master Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'vendor_name' => Yii::t('app', 'Vendor Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'vendor_type' => Yii::t('app', 'Vendor Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'ledger_code' => Yii::t('app', 'Ledger Code'),
        ];
    }

}
