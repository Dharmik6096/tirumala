<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_transporter_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $transporter_code
 * @property string $transporter_name
 * @property string $local_name
 * @property string $address
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $email
 * @property string $pincode
 * @property string $registration_no
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $gstin
 * @property string $tds_per
 * @property string $pan_no
 * @property string $beneficiary_name
 * @property string $agreement_no
 * @property string $declaration
 * @property string $security_cheque_no
 * @property string $union_code
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 */
class TblTransporterHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_transporter_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id'], 'required'],
            [['id', 'is_active'], 'safe'],
            [['history_created_at', 'created_at', 'updated_at', 'security_amount'], 'safe'],
            [['operation_type', 'transporter_code', 'transporter_name', 'local_name', 'address', 'phone_no', 'mobile_no', 'email', 'pincode', 'registration_no', 'contact_person', 'local_contact_person', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'gstin', 'pan_no', 'beneficiary_name', 'agreement_no', 'declaration', 'security_cheque_no', 'union_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'created_by', 'updated_by'], 'safe'],
            [['tds_per'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'transporter_name' => Yii::t('app', 'Transporter Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'address' => Yii::t('app', 'Address'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Local Contact Person'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'gstin' => Yii::t('app', 'GSTIN'),
            'tds_per' => Yii::t('app', 'Tds Per'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'agreement_no' => Yii::t('app', 'Agreement No'),
            'declaration' => Yii::t('app', 'Declaration'),
            'security_cheque_no' => Yii::t('app', 'Security Cheque No'),
            'union_code' => Yii::t('app', 'Union Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'security_amount'=>Yii::t('app', 'Security Amount'),
        ];
    }
}
