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

/**
 * This is the model class for table "tbl_product_requisition".
 *
 * @property string $product_requisition_code
 * @property string $req_date
 * @property string $description
 * @property string $status
 * @property string $vendor_type
 * @property string $vendor_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
class TblProductRequisition extends \app\models\ChildModel {

    public $operation = TRUE;
    public $plant_name, $mcc_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_requisition';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vendor_type', 'req_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required'],
                [['product_requisition_code'], 'safe'],
                [['dcs_code'], 'required', 'when' => function ($model) {
                    return $model->vendor_type == 'DCS';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductrequisition-vendor_type').val() == 'DCS'; 
          }"],
                [['product_requisition_code', 'description', 'status', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['req_date', 'created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
                [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'req_date' => Yii::t('app', 'Requisition Date'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'vendor_type' => Yii::t('app', 'Type'),
            'vendor_code' => Yii::t('app', 'Name'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
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
            'plant_name' => Yii::t('app', 'Plant'),
            'mcc_name' => Yii::t('app', 'MCC'),
        ];
    }

    public function addProductRequisition($records) {
        $this->attributes = $records;
        $this->product_requisition_code = Yii::$app->general->getPrimaryCode($this);
    }

    public function getRecord($code) {
        return $this->find()->select('*')->where(['product_requisition_code' => $code])->one();
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

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function reqStatus() {
        return [1 => 'Draft', 6 => 'Sent', 11 => 'Rejected', 16 => 'Partially Processed', 21 => 'InProcess', 26 => 'Partially Dispatched', 31 => 'Partially Delivered', 36 => 'Dispatched', 41 => 'Delivered', 46 => 'Accepted', 51 => 'Partially Accepted', 56 => 'Closed', 61 => 'Pending'];
    }

    public function getRequisitionStatus($status) {

        switch ($status) {
            case 1:
                return 'Draft';
                break;
            case 6:
                return 'Sent';
                break;
            case 11:
                return 'Rejected';
                break;
            case 16:
                return 'Partially Processed';
                break;
            case 21:
                return 'InProcess';
                break;
            case 26:
                return 'Partially Dispatched';
                break;
            case 31:
                return 'Partially Delivered';
                break;
            case 36:
                return 'Dispatched';
                break;
            case 41:
                return 'Delivered';
                break;
            case 46:
                return 'Accepted';
                break;
            case 51:
                return 'Partially Accepted';
                break;
            case 56:
                return 'Closed';
                break;
            case 61:
                return 'Pending';
                break;
        }
    }

    public function getTblProductRequisitionTransactions() {
        return $this->hasMany(TblProductRequisitionTransaction::className(), ['product_requisition_code' => 'product_requisition_code'])/* ->andWhere('is_approved is null') */;
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

}
