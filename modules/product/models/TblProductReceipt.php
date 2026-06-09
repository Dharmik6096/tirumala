<?php

namespace app\modules\product\models;

use Yii;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_product_receipt".
 *
 * @property string $product_receipt_code
 * @property string $grn_no
 * @property string $grn_date
 * @property string $challan_no
 * @property string $challan_date
 * @property integer $challan_verified
 * @property string $description
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
class TblProductReceipt extends \app\models\ChildModel {

    public $is_sentbox = TRUE;
    public $customer_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_receipt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_receipt_code'], 'required', 'on' => ['androidsync']],
                [['product_receipt_code', 'grn_no', 'challan_no', 'description', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['grn_date', 'challan_date', 'created_at', 'updated_at'], 'safe'],
                [['challan_verified', 'originating_type', 'bill_no'], 'safe'],
                [['challan_verified'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_receipt_code' => Yii::t('app', 'Product Receipt Code'),
            'grn_no' => Yii::t('app', 'GRN No.'),
            'grn_date' => Yii::t('app', 'GRN Date'),
            'challan_no' => Yii::t('app', 'Challan No.'),
            'challan_date' => Yii::t('app', 'Challan Date'),
            'challan_verified' => Yii::t('app', 'Challan Verified'),
            'description' => Yii::t('app', 'Description'),
            'vendor_type' => Yii::t('app', 'Type'),
            'vendor_code' => Yii::t('app', 'Code'),
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
            'bill_no' => Yii::t('app', 'Bill No.'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
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

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        if (!empty($this->dcs_code)) {
            $array = [];
            $array['code'] = $this->dcs_code;
            $array['type'] = 'VLC';
            $sentboxArray = [$array];
        } else if (!empty($this->bmc_code)) {
            $array = [];
            $array['code'] = $this->bmc_code;
            $array['type'] = 'BMC';
            $sentboxArray = [$array];
        } else if (!empty($this->mcc_plant_code)) {
            $array = [];
            $array['code'] = $this->mcc_plant_code;
            $array['type'] = 'MCC';
            $sentboxArray = [$array];
        }
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
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

}
