<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\tankermovement\models\TblPartyMaster;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\tankermovement\models\TblMaterialMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_raw_fg_material_receipt".
 *
 * @property string $raw_fg_material_receipt_code
 * @property string $receipt_datetime
 * @property string $party_code
 * @property string $vehicle_code
 * @property integer $material_code
 * @property string $document_type
 * @property string $document_date
 * @property string $document_no
 * @property string $gross_weight
 * @property string $gross_weight_datetime
 * @property string $tare_weight
 * @property string $tare_weight_datetime
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblRawFgMaterialReceipt extends \app\models\ChildModel {

    public $material_ref_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_raw_fg_material_receipt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['raw_fg_material_receipt_code'], 'required', 'except' => ['androidsync']],
                [['material_ref_code', 'union_code', 'plant_code', 'document_type', 'document_no', 'raw_fg_material_receipt_code', 'party_code', 'vehicle_code', 'gross_weight', 'tare_weight', 'material_code', 'originating_type', 'receipt_datetime', 'document_date', 'gross_weight_datetime', 'tare_weight_datetime', 'created_at', 'updated_at', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'material_entry_type', 'remarks', 'dock_no', 'tanker_no', 'party_name', 'receipt_seq_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'raw_fg_material_receipt_code' => Yii::t('app', 'Raw Fg Material Receipt Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'receipt_datetime' => Yii::t('app', 'Receipt Datetime'),
            'party_code' => Yii::t('app', 'Party'),
            'vehicle_code' => Yii::t('app', 'Vehicle No'),
            'material_code' => Yii::t('app', 'Material Name'),
            'document_type' => Yii::t('app', 'Document Type'),
            'document_date' => Yii::t('app', 'Document Date'),
            'document_no' => Yii::t('app', 'Document No'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'gross_weight_datetime' => Yii::t('app', 'Gross Weight Datetime'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'tare_weight_datetime' => Yii::t('app', 'Tare Weight Datetime'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'party_name' => Yii::t('app', 'Party Name'),
            'receipt_seq_number' => Yii::t('app', 'Receipt Seq. Number'),
        ];
    }

    public function getPartyMaster() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'party_code']);
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getMaterialCode() {
        return $this->hasOne(TblMaterialMaster::className(), ['material_code' => 'material_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

}
