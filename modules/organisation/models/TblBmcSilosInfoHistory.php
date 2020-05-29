<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_silos_info_history".
 *
 * @property integer $id
 * @property integer $bmc_silos_info_code
 * @property string $silo_no
 * @property string $description
 * @property integer $manufacturer_code
 * @property string $model
 * @property string $wef_date
 * @property integer $storage_capacity
 * @property integer $chilling_capacity
 * @property string $owning_type
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property string $module_name
 * @property string $module_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblBmcSilosInfoHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_silos_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_silos_info_code', 'manufacturer_code', 'storage_capacity', 'chilling_capacity', 'milk_type_code', 'is_active', 'originating_type'], 'safe'],
            [['silo_no', 'description', 'model', 'owning_type', 'module_name', 'module_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'silo_no' => Yii::t('app', 'Silo No'),
            'description' => Yii::t('app', 'Description'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer Code'),
            'model' => Yii::t('app', 'Model'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'storage_capacity' => Yii::t('app', 'Storage Capacity'),
            'chilling_capacity' => Yii::t('app', 'Chilling Capacity'),
            'owning_type' => Yii::t('app', 'Owning Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
