<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\product\models\TblVendorMaster;

/**
 * This is the model class for table "tbl_grn".
 *
 * @property string $grn_code
 * @property string $grn_no
 * @property string $grn_date
 * @property string $vendor_master_code
 * @property string $mcc_plant_code
 * @property string $invoice_date
 * @property string $invoice_no
 * @property string $remarks
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblGrn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_grn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'grn_no', 'grn_date', 'mcc_plant_code', 'vendor_master_code', 'invoice_date'], 'required'],
            [['grn_code', 'grn_date', 'invoice_date', 'created_at', 'updated_at'], 'safe'],
            [['remarks', 'originating_type', 'union_code'], 'safe'],
            [['grn_no', 'invoice_no'], 'string', 'max' => 30],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'grn_code' => Yii::t('app', 'Grn Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'grn_date' => Yii::t('app', 'Grn Date'),
            'vendor_master_code' => Yii::t('app', 'Vendor'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'invoice_no' => Yii::t('app', 'Invoice No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getVendorCode() {
        return $this->hasOne(TblVendorMaster::className(), ['vendor_master_code' => 'vendor_master_code']);
    }

}
