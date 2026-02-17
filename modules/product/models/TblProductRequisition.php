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
use app\modules\syncutility\models\TblSentbox;

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
    public $plant_name, $mcc_name, $customer_name, $route_code;
    public $is_sentbox = TRUE;
    public $req_time;

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
                [['vendor_type', 'req_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'except' => ['androidsync']],
                [['product_requisition_code'], 'safe'],
                [['product_requisition_code'], 'required', 'on' => ['androidsync']],
                [['dcs_code'], 'required', 'when' => function ($model) {
                    return $model->vendor_type == 'DCS';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductrequisition-vendor_type').val() == 'DCS'; 
          }", 'except' => ['androidsync']],
                [['product_requisition_code', 'description', 'status', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['req_date', 'created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'safe'],
                [['description'], 'string', 'max' => 500],
                [['req_time'], 'safe'],
                [['req_time'], 'required', 'on' => ['create']],
                [['req_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01')],
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
            'route_code' => Yii::t('app', 'Route'),
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

    public function getEntityRefCode() {
        $type = $this->vendor_type;
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getforeignkey($this->bmcCode, 'ref_code');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getforeignkey($this->dcsCode, 'ref_code');
        }
        return $name;
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $type = $this->vendor_type;
        if (!empty($type)) {
            $type = $type == 'DCS' ? 'VLC' : $type;
            $sentboxArray[] = [
                'code' => $this->vendor_code,
                'type' => $type
            ];
        }
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getApprovedRequisitionTransactions($reqCode, $dispCode) {
        $productData = Yii::$app->general->getDispCenterProducts($dispCode);
        $subQuery = (new \yii\db\Query())
                ->select('sum(dispatch_qty) as dispatch_qty,product_code,product_requisition_code,requisition_transaction_code')
                ->from('tbl_product_dispatch_transaction AS t')
                ->groupBy(['product_requisition_code', 'product_code', 'requisition_transaction_code']);

        $rows = (new \yii\db\Query())
                ->select('prt.*,product_name,ref_code')
                ->from('tbl_product_requisition_transaction AS prt')
                ->innerJoin('tbl_product', 'tbl_product.product_code=prt.product_code')
                ->leftJoin(['x' => $subQuery], 'x.requisition_transaction_code=prt.requisition_transaction_code')
                ->where("is_approved=1 and (dispatch_qty < prt.approved_quantity OR dispatch_qty is null) and prt.product_requisition_code='" . $reqCode . "' and prt.status in ('Under Dispatch')");

        if ($productData['pass_where_close'] == 'Yes') {
            $rows->andWhere(['prt.product_code' => $productData['product_list']]);
        }
        return $rows->all();
    }

}
