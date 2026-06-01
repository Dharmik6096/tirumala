<?php

namespace app\modules\insurance\models;

use Yii;

/**
 * This is the model class for table "tbl_insurance_detail_history".
 *
 * @property integer $id
 * @property integer $insurance_detail_code
 * @property integer $insurance_master_code
 * @property string $sr_no
 * @property string $union_code
 * @property string $plant_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $dcs_code
 * @property string $dcs_name
 * @property string $member_id
 * @property string $member_code
 * @property string $member_name
 * @property string $adhar_no
 * @property string $dob
 * @property integer $age
 * @property string $gender_code
 * @property string $nominee_adhar_no
 * @property string $nominee_member_name
 * @property string $status
 * @property integer $is_delete
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInsuranceDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_insurance_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'insurance_detail_code', 'insurance_master_code', 'sr_no', 'union_code', 'plant_code', 'bmc_code', 'mcc_plant_code', 'dcs_code', 'dcs_name', 'member_id', 'member_code', 'member_name', 'adhar_no', 'dob', 'age', 'gender_code', 'nominee_adhar_no', 'nominee_member_name', 'mobile_no', 'date_of_joining_scheme', 'status', 'is_delete', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'history_created_at', 'history_created_by', 'operation_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'sys_updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'insurance_detail_code' => Yii::t('app', 'Insurance Detail Code'),
            'insurance_master_code' => Yii::t('app', 'Insurance Master Code'),
            'sr_no' => Yii::t('app', 'Sr No'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'dcs_name' => Yii::t('app', 'Dcs Name'),
            'member_id' => Yii::t('app', 'Member ID'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'adhar_no' => Yii::t('app', 'Adhar No'),
            'dob' => Yii::t('app', 'Dob'),
            'age' => Yii::t('app', 'Age'),
            'gender_code' => Yii::t('app', 'Gender'),
            'nominee_adhar_no' => Yii::t('app', 'Nominee Adhar No'),
            'nominee_member_name' => Yii::t('app', 'Nominee Member Name'),
            'date_of_joining_scheme' => Yii::t('app', 'Date Of Joining Scheme'),
            'status' => Yii::t('app', 'Status'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
