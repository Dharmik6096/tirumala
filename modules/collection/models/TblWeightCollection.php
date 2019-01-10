<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_weight_collection".
 *
 * @property string $uuid
 * @property string $producer_flag
 * @property integer $sample_no
 * @property string $collection_date
 * @property string $shift_code
 * @property integer $milk_type
 * @property integer $milk_quality_type
 * @property integer $quantity_mode
 * @property string $quantity
 * @property double $cans
 * @property string $fault_flag
 * @property integer $rejected_can
 * @property string $rejected_quantity
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $device_id
 * @property string $converted_quantity_mode
 * @property string $converted_quantity
 */
class TblWeightCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_weight_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid'], 'required'],
            [['uuid', 'producer_flag', 'shift_code', 'fault_flag', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'dcs_code', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'device_id', 'converted_quantity_mode', 'converted_quantity'], 'safe'],
            [['sample_no', 'milk_type', 'milk_quality_type', 'quantity_mode', 'rejected_can'], 'safe'],
            [['collection_date', 'created_at', 'updated_at', 'sync_timestamp'], 'safe'],
            [['quantity', 'cans', 'rejected_quantity'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'producer_flag' => Yii::t('app', 'Producer Flag'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'collection_date' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'milk_quality_type' => Yii::t('app', 'Milk Quality Type'),
            'quantity_mode' => Yii::t('app', 'Quantity Mode'),
            'quantity' => Yii::t('app', 'Quantity'),
            'cans' => Yii::t('app', 'Cans'),
            'fault_flag' => Yii::t('app', 'Fault Flag'),
            'rejected_can' => Yii::t('app', 'Rejected Can'),
            'rejected_quantity' => Yii::t('app', 'Rejected Quantity'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_code' => Yii::t('app', 'Mcc Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

}
