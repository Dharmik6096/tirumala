<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_bmc_transit_loss".
 *
 * @property integer $transit_loss_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $qty
 * @property string $amount
 * @property string $loss_amount
 * @property integer $loss_applied_to
 * @property string $transporter_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBmcTransitLoss extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_transit_loss';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'from_shift', 'to_shift', 'union_code'], 'required', 'except' => ['updateLossType']],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'shift_code', 'transporter_code', 'remarks', 'created_by', 'updated_by'], 'string'],
            [['date_time_of_collection', 'created_at', 'updated_at', 'dcs_code'], 'safe'],
            [['kg_fat', 'kg_snf', 'qty', 'amount', 'loss_amount'], 'number'],
            [['loss_applied_to'], 'integer'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transit_loss_code' => Yii::t('app', 'Transit Loss Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'kg_fat' => Yii::t('app', 'Kg FAT'),
            'kg_snf' => Yii::t('app', 'Kg SNF'),
            'qty' => Yii::t('app', 'Qty'),
            'amount' => Yii::t('app', 'Amount'),
            'loss_amount' => Yii::t('app', 'Loss Amount'),
            'loss_applied_to' => Yii::t('app', 'Loss Applied To'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

}
