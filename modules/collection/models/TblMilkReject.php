<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\globalmaster\models\TblRejectionReason;
use app\modules\organisation\models\TblMccPlant;
use app\modules\globalmaster\models\TblRejectionResponsibility;

/**
 * This is the model class for table "tbl_milk_reject".
 *
 * @property integer $milk_reject_code
 * @property string $source_org_type
 * @property string $source_org_code
 * @property string $dest_org_type
 * @property string $dest_org_code
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property string $return_type
 * @property string $action_taken
 * @property string $remarks
 * @property string $fat
 * @property string $snf
 * @property integer $no_of_can
 * @property string $qty
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $rejection_reason_code
 * @property integer $rejection_responsibility_code
 * @property string $date_time_of_collection
 * @property integer $sample_no
 * @property integer $qty_mode
 * @property string $device_id
 * @property string $version_no
 * @property integer $doc_no
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $clr
 * @property string $water
 * @property string $route_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkReject extends \app\models\ChildModel {

    public $customer_code, $customer_type, $is_active;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_reject';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['source_org_type', 'rejection_reason_code', 'return_type', 'action_taken', 'remarks', 'union_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'date_time_of_collection', 'action_taken', 'fat', 'snf', 'no_of_can', 'qty'], 'required', 'except' => ['rejectRespMap']],
                [['source_org_type', 'source_org_code', 'dest_org_type', 'dest_org_code', 'return_type', 'action_taken', 'remarks', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'device_id', 'version_no', 'vehicle_code', 'parsing_no', 'route_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['shift_code', 'milk_type_code', 'no_of_can', 'originating_type', 'rejection_reason_code', 'rejection_responsibility_code', 'sample_no', 'qty_mode', 'doc_no'], 'safe'],
                [['fat', 'snf', 'qty', 'clr', 'water'], 'number'],
                [['created_at', 'updated_at', 'date_time_of_collection', 'customer_code', 'customer_type'], 'safe'],
                [['fat', 'snf', 'snf'], 'number'],
                [['fat', 'snf', 'qty', 'no_of_can'], 'number', 'min' => 0],
                [['customer_code', 'customer_type'], 'required', 'when' => function ($model) {
                    return ($model->source_org_type == 'bmc');
                }, 'whenClient' => "function (attribute, value) { 
              return ($('#tblmilkreject-source_org_type').val() == 'bmc'); 
          }", 'except' => ['rejectRespMap']],
                [['water'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_reject_code' => Yii::t('app', 'Milk Reject Code'),
            'source_org_type' => Yii::t('app', 'Receipt At'),
            'source_org_code' => Yii::t('app', 'Source'),
            'dest_org_type' => Yii::t('app', 'Destination Type'),
            'dest_org_code' => Yii::t('app', 'Destination'),
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'return_type' => Yii::t('app', 'Return Type'),
            'action_taken' => Yii::t('app', 'Action Taken'),
            'remarks' => Yii::t('app', 'Remarks'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'qty' => Yii::t('app', 'QTY'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'rejection_reason_code' => Yii::t('app', 'Rejection Reason'),
            'rejection_responsibility_code' => Yii::t('app', 'Rejection Responsibility Code'),
            'date_time_of_collection' => Yii::t('app', 'Date'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'route_code' => Yii::t('app', 'Route Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'plant_code' => Yii::t('app', 'Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'customer_code' => Yii::t('app', 'Code'),
            'customer_type' => Yii::t('app', 'Type'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getSourceBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'source_org_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'dest_org_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dest_org_code']);
    }

    public function getSourcePlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'source_org_code']);
    }

    public function getDestBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'dest_org_code']);
    }

    public function getRejectReason() {
        return $this->hasOne(TblRejectionReason::className(), ['rejection_reason_code' => 'rejection_reason_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getSampleNo() {
        $data = $this->find()
                ->select('max(sample_no) as sample_no')
                ->where(['bmc_code' => $this->bmc_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->formatter->asDate($this->date_time_of_collection, DATE_FORMAT)])
                ->one();
        $sample_no = (int) $data['sample_no'] + 1;
        return $sample_no;
    }

    public function getRejectResponsibility() {
        return $this->hasOne(TblRejectionResponsibility::className(), ['rejection_responsibility_code' => 'rejection_responsibility_code']);
    }

}
