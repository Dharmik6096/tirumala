<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_plant_history".
 *
 * @property integer $id
 * @property string $plant_code
 * @property string $contact_person
 * @property string $name
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $local_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $union_code
 * @property integer $is_active
 * @property string $mobile_no
 * @property string $local_contact_person_name
 * @property string $email
 * @property string $description
 * @property string $operation_type
 * @property string $capacity
 */
class TblPlantHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'plant_code', 'contact_person', 'name', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'village_code', 'local_name', 'created_by', 'updated_by', 'union_code', 'mobile_no', 'local_contact_person_name', 'email', 'description', 'created_at', 'updated_at', 'history_created_at', 'operation_type', 'capacity', 'valid_from'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'sap_vendor_code', 'is_virtual_plant'], 'safe'],
                [['plant_code_ex', 'ref_code', 'vendor_code', 'auto_code', 'history_created_by'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'plant_code' => Yii::t('app', 'Plant Code'),
//            'contact_person' => Yii::t('app', 'Contact Person'),
//            'name' => Yii::t('app', 'Name'),
//            'district_code' => Yii::t('app', 'District Code'),
//            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
//            'state_code' => Yii::t('app', 'State Code'),
//            'sub_district_code' => Yii::t('app', 'Sub District Code'),
//            'village_code' => Yii::t('app', 'Village Code'),
//            'local_name' => Yii::t('app', 'Local Name'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'updated_by' => Yii::t('app', 'Updated By'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'union_code' => Yii::t('app', 'Union Code'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'mobile_no' => Yii::t('app', 'Mobile No'),
//            'local_cantact_person_name' => Yii::t('app', 'Local Cantact Person Name'),
//            'email' => Yii::t('app', 'Email'),
//            'description' => Yii::t('app', 'Description'),
        ];
    }

}
