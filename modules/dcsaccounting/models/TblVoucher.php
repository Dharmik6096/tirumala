<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsaccounting\models\TblVoucherTypes;

/**
 * This is the model class for table "tbl_voucher".
 *
 * @property string $voucher_code
 * @property integer $auto_posted
 * @property integer $cancelled
 * @property string $bill_date
 * @property string $voucher_date
 * @property string $bill_no
 * @property string $remarks
 * @property integer $voucher_type_code
 * @property string $dock_code
 * @property string $financial_year_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVoucher extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['voucher_code', 'bill_no', 'remarks', 'dock_code', 'financial_year_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'auto_posted', 'cancelled', 'voucher_type_code', 'originating_type', 'bill_date', 'voucher_date', 'created_at', 'updated_at'], 'safe'],
                [['voucher_code'], 'required', 'on' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'voucher_code' => Yii::t('app', 'Voucher Code'),
            'auto_posted' => Yii::t('app', 'Auto Posted?'),
            'cancelled' => Yii::t('app', 'Is Cancelled?'),
            'bill_date' => Yii::t('app', 'Bill Date'),
            'voucher_date' => Yii::t('app', 'Voucher Date'),
            'bill_no' => Yii::t('app', 'Bill No.'),
            'remarks' => Yii::t('app', 'Remarks'),
            'voucher_type_code' => Yii::t('app', 'Voucher Type'),
            'dock_code' => Yii::t('app', 'Dock Code'),
            'financial_year_code' => Yii::t('app', 'Financial Year'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
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

    public function getvoucherTypeCode() {
        return $this->hasOne(TblVoucherTypes::className(), ['voucher_type_code' => 'voucher_type_code']);
    }

}
