<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_plant_conversion_vendor_mapping_history".
 *
 * @property integer $id
 * @property integer $conversion_vendor_mapping_id
 * @property integer $party_master_code
 * @property string $union_code
 * @property string $plant_code
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
 */
class TblPlantConversionVendorMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_conversion_vendor_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['conversion_vendor_mapping_id', 'party_master_code', 'union_code', 'plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'conversion_vendor_mapping_id' => Yii::t('app', 'Conversion Vendor Mapping ID'),
            'party_master_code' => Yii::t('app', 'Party Master Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
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
        ];
    }

}
