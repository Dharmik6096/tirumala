<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_member_bill_head".
 *
 * @property string $bill_head_code
 * @property string $bill_head_name
 * @property string $local_name
 * @property integer $is_default_head
 * @property integer $is_disburse_allowed
 * @property integer $head_type
 * @property integer $allow_adjustment
 * @property integer $is_active
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
class TblMemberBillHead extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_bill_head';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'bill_head_name', 'bill_head_code', 'originating_org_code', 'originating_org_type', 'is_default_head', 'is_disburse_allowed', 'head_type', 'allow_adjustment', 'is_active', 'originating_type', 'created_at', 'updated_at', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['bill_head_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_name' => Yii::t('app', 'Bill Head Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'is_default_head' => Yii::t('app', 'Is Default Head?'),
            'is_disburse_allowed' => Yii::t('app', 'Is Disburse Allowed?'),
            'head_type' => Yii::t('app', 'Head Type'),
            'allow_adjustment' => Yii::t('app', 'Allow Adjustment?'),
            'is_active' => Yii::t('app', 'Is Active'),
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

}
