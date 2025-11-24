<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_medicine_stock_transfer_txn".
 *
 * @property integer $medicine_stock_transfer_txn_code
 * @property string $medicine_stock_transfer_code
 * @property string $union_code
 * @property integer $medicine_id
 * @property string $batch_no
 * @property string $qty
 * @property string $expire_date
 * @property string $available_stock
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
class TblMedicineStockTransferTxn extends ChildModel {

    public $transaction_date, $remarks, $plant_code, $mcc_plant_code, $bmc_code, $dcs_code, $from_user_code, $to_user_code, $medicine_wise;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_stock_transfer_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'batch_no', 'qty', 'available_stock'], 'required'],
            [['medicine_id', 'originating_type'], 'integer'],
            [['qty', 'available_stock'], 'number'],
            [['qty'], 'number', 'min' => 1],
            [['qty'], 'validateQty'],
            [['medicine_stock_transfer_code', 'union_code', 'medicine_id', 'batch_no', 'qty', 'expire_date', 'available_stock', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'transaction_date', 'remarks', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_user_code', 'to_user_code', 'medicine_wise', 'rate'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['batch_no'], 'string', 'max' => 500],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'medicine_stock_transfer_txn_code' => Yii::t('app', 'Medicine Stock Transfer Txn Code'),
            'medicine_stock_transfer_code' => Yii::t('app', 'Medicine Stock Transfer Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'qty' => Yii::t('app', 'Qty'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'available_stock' => Yii::t('app', 'Available Stock'),
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
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_user_code' => Yii::t('app', 'From User Code'),
            'to_user_code' => Yii::t('app', 'To User Code'),
            'medicine_wise' => Yii::t('app', 'Medicine Wise'),
        ];
    }

    public function validateQty($attribute, $params) {
        if ($this->$attribute > $this->available_stock) {
            $this->addError($attribute, 'Qty cannot be greater than available stock.');
        }
    }

    public function getMedicineMasterCode() {
        return $this->hasOne(TblMedicineMaster::className(), ['medicine_id' => 'medicine_id']);
    }

}
