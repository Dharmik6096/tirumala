<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_dcs_closing".
 *
 * @property string $dcs_closing_code
 * @property string $transaction_date
 * @property string $to_date
 * @property integer $to_shift_code
 * @property integer $milk_type_code
 * @property string $qty
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $protein
 * @property string $remarks
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
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
 */
class TblDcsClosing extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_closing';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_closing_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'transaction_date', 'to_date', 'to_shift_code', 'milk_type_code', 'qty'], 'required'],
            [['transaction_date', 'to_date', 'created_at', 'updated_at', 'dcs_code', 'fat', 'snf'], 'safe'],
            [['to_shift_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'protein'], 'number'],
            [['dcs_closing_code'], 'string', 'max' => 35],
            [['remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['milk_type_code'], 'unique', 'targetAttribute' => ['milk_type_code', 'dcs_code', 'to_date', 'to_shift_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['importCsv', 'importLimitedCsv', 'deactivate', 'customImport', 'saveCreamyData', 'post_sap_data', 'androidsync', 'ApprovalMember', 'verification']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_closing_code' => 'Dcs Closing Code',
            'transaction_date' => 'Transaction Date',
            'to_date' => 'To Date',
            'to_shift_code' => 'To Shift',
            'milk_type_code' => 'Milk Type',
            'qty' => 'Qty',
            'fat' => 'FAT',
            'snf' => 'SNF',
            'water' => 'Water',
            'protein' => 'Protein',
            'remarks' => 'Remarks',
            'union_code' => 'Union',
            'plant_code' => 'Plant',
            'mcc_plant_code' => 'MCC',
            'bmc_code' => 'BMC',
            'dcs_code' => 'DCS',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'x_col1' => 'X Col1',
            'x_col2' => 'X Col2',
            'x_col3' => 'X Col3',
            'x_col4' => 'X Col4',
            'x_col5' => 'X Col5',
        ];
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

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift_code']);
    }

}
