<?php

namespace app\modules\product\models;

use Yii;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\product\models\TblIndentDispatchTransaction;
use app\modules\product\models\TblProduct;
use app\modules\product\models\TblIndentMaster;

/**
 * This is the model class for table "tbl_indent_dispatch".
 *
 * @property string $indent_dispatch_code
 * @property string $challan_date
 * @property string $reference_no
 * @property string $dispatch_date
 * @property string $customer_type
 * @property string $customer_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $vehicle_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblIndentDispatch extends \app\models\ChildModel {

    public $vehicle, $ref_no, $lrno, $remaining_qty;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_indent_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['indent_dispatch_code'], 'required'],
                [['challan_date', 'dispatch_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_type', 'customer_code', 'reference_no', 'route_code', 'vehicle_no', 'indent_code', 'product_code', 'status', 'rate', 'amount', 'discount_amount', 'dispatch_qty', 'lr_no', 'remaining_qty'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['vehicle', 'ref_no', 'lrno'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'indent_dispatch_code' => Yii::t('app', 'Indent Dispatch Code'),
            'challan_date' => Yii::t('app', 'Challan Date'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'product_code' => Yii::t('app', 'Product'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
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

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_no']);
    }

    public function getIndentDispatchTxn() {
        return $this->hasMany(TblIndentDispatchTransaction::className(), ['indent_dispatch_code' => 'indent_dispatch_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getIndentCode() {
        return $this->hasOne(TblIndentMaster::className(), ['indent_code' => 'indent_code']);
    }

}
