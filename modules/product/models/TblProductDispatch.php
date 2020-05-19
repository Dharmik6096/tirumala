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

/**
 * This is the model class for table "tbl_product_dispatch".
 *
 * @property string $challan_no
 * @property string $challan_date
 * @property integer $challan_verified
 * @property string $reference_no
 * @property string $dispatch_date
 * @property string $vendor_type
 * @property string $vendor_code
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
class TblProductDispatch extends \app\models\ChildModel {

    public $plant_name, $mcc_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['challan_no'], 'required'],
                [['challan_no', 'reference_no', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'vehicle_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
                [['challan_date', 'dispatch_date', 'created_at', 'updated_at'], 'safe'],
                [['challan_verified', 'originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'challan_no' => Yii::t('app', 'Challan No.'),
            'challan_date' => Yii::t('app', 'Date'),
            'challan_verified' => Yii::t('app', 'Challan Verified'),
            'reference_no' => Yii::t('app', 'Reference No.'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'vendor_type' => Yii::t('app', 'Type'),
            'vendor_code' => Yii::t('app', 'Name'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route'),
            'vehicle_no' => Yii::t('app', 'Vehicle No.'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'vendor_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'vendor_type']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'vendor_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'vendor_code']);
    }

    public function getEntityName() {
        $type = $this->vendor_type;
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getforeignkey($this->bmcCode, 'bmc_name');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name');
        }
        return $name;
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

}
