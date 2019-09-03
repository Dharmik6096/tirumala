<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\configuration\models\TblMilkCollectionConfig;

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
 * @property integer $doc_no
 * @property integer $auto_flag
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
            [['dcs_code', 'collection_date', 'sample_no', 'shift_code', 'milk_type', 'milk_quality_type', 'doc_no', 'quantity'], 'required', 'on' => ['PortalCreate']],
            [['uuid', 'producer_flag', 'shift_code', 'fault_flag', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'route_code', 'dcs_code', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'device_id', 'converted_quantity_mode', 'converted_quantity', 'auto_flag', 'doc_no'], 'safe'],
            [['sample_no', 'milk_type', 'milk_quality_type', 'quantity_mode', 'rejected_can'], 'safe'],
            [['collection_date', 'created_at', 'updated_at', 'sync_timestamp'], 'safe'],
            [['quantity', 'cans', 'rejected_quantity'], 'safe'],
            [['cans'], 'integer'],
            [['quantity'], 'double', 'min' => 0, 'max' => 99999, 'on' => ['edit_collection']],
            [['sample_no'], 'unique', 'targetAttribute' => ['collection_date', 'shift_code', 'mcc_code', 'sample_no', 'doc_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['producer_flag'], 'default', 'value' => 'Y'],
            [['cans', 'rejected_can', 'rejected_quantity'], 'default', 'value' => '0'],
            [['auto_flag'], 'default', 'value' => '1'],
            [['fault_flag'], 'default', 'value' => 'N'],
            [['collection_date', 'shift_code'], 'backendData']
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
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'milk_quality_type' => Yii::t('app', 'Milk Quality Type'),
            'quantity_mode' => Yii::t('app', 'Quantity Mode'),
            'quantity' => Yii::t('app', 'Qty'),
            'cans' => Yii::t('app', 'No. of Can'),
            'fault_flag' => Yii::t('app', 'Fault Flag'),
            'rejected_can' => Yii::t('app', 'Rejected Can'),
            'rejected_quantity' => Yii::t('app', 'Rejected Qty'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_code' => Yii::t('app', 'Mcc Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'converted_quantity' => Yii::t('app', 'Converted Qty'),
            'doc_no' => Yii::t('app', 'Doc No.'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getMilkQualityTypeCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type']);
    }

    public function backendData() {
        $milkCollModel = new TblMilkCollectionConfig();
        $milkCollModel->union_code = $this->union_code;
        $milkCollData = $milkCollModel->getData();

        if (!empty($milkCollData) && $milkCollData->ltr_to_kg != 0 && !empty($milkCollData->ltr_to_kg)) {
            if ($milkCollData->collection_quantity_mode == 0) {
                $this->converted_quantity = $this->quantity * $milkCollData->ltr_to_kg;
                $this->quantity_mode = 0;
                $this->converted_quantity_mode = 1;
            } else {
                $this->converted_quantity = $this->quantity / $milkCollData->ltr_to_kg;
                $this->quantity_mode = 1;
                $this->converted_quantity_mode = 0;
            }
        }
    }

}
