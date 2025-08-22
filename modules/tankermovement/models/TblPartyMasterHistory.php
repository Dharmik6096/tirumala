<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_transaction_history".
 *
 * @property integer $id
 * @property string $milk_vehicle_entry_transaction_code
 * @property string $milk_vehicle_entry_code
 * @property string $vehicle_entry_chamber_date
 * @property string $chamber_quantity
 * @property string $grn_no
 * @property string $chamber_no
 * @property string $challan_no
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $source_org_code
 * @property string $source_org_type
 * @property string $destination_code
 * @property string $destination_type
 * @property string $entry_type
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblPartyMasterHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_party_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['party_master_code', 'union_code', 'party_name', 'party_contact_no', 'party_address', 'owner_name', 'owner_contact_no', 'owner_email', 'owner_address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'pan_no', 'adhar_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'sap_vendor_code', 'is_sales_office', 'party_type'], 'safe'],
            [['is_active', 'originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'party_master_code' => Yii::t('app', 'Party Master Code'),
            'union_code' => Yii::t('app', 'Union'),
            'party_name' => Yii::t('app', 'Party Name'),
            'party_contact_no' => Yii::t('app', 'Party Contact No'),
            'party_address' => Yii::t('app', 'Party Address'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'owner_contact_no' => Yii::t('app', 'Owner Contact No'),
            'owner_email' => Yii::t('app', 'Owner Email'),
            'owner_address' => Yii::t('app', 'Owner Address'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'adhar_no' => Yii::t('app', 'Adhar No'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'is_sales_office' => Yii::t('app', 'Is Sales Office'),
        ];
    }
}
