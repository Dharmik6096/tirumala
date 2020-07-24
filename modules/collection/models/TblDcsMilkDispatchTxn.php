<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblDcs;
use app\modules\collection\models\TblCollectionDataAlias;

/**
 * This is the model class for table "tbl_dcs_milk_dispatch_txn".
 *
 * @property integer $dcs_milk_dispatch_txn_code
 * @property integer $dcs_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $nos_of_can
 * @property string $dispatch_qty
 * @property string $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $avg_clr
 * @property string $water
 * @property string $temperature
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $total_amount
 */
class TblDcsMilkDispatchTxn extends \app\models\ChildModel {

    public $from_date, $to_date, $from_shift, $to_shift, $status, $dispatch_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_milk_dispatch_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_milk_dispatch_code', 'dcs_milk_dispatch_txn_code'], 'safe', 'on' => ['androidsync']],
            [['dcs_milk_dispatch_code', 'milk_quality_type_code', 'milk_type_code', 'nos_of_can', 'converted_qty_mode'], 'safe'],
            [['dispatch_qty', 'qty_mode', 'converted_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'water', 'temperature', 'total_amount', 'purchase_rate_code', 'rtpl'], 'safe'],
            [['dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_at', 'updated_at', 'dcs_milk_dispatch_txn_code'], 'safe'],
            [['dcs_code', 'milk_type_code', 'milk_quality_type_code', 'dispatch_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'rtpl', 'total_amount'], 'required', 'on' => ['create', 'update']],
            [['water', 'avg_clr'], 'default', 'value' => 0],
            [['nos_of_can'], 'integer', 'min' => 0, 'on' => ['create', 'update']],
            [['avg_clr'], 'double', 'min' => 0, 'on' => ['create', 'update']],
//            [['dcs_code'], 'validateUnique', 'on' => ['create']],
//            [['milk_type_code'], 'validateUpdate', 'on' => ['update']],
            [['dispatch_qty'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0'), 'on' => ['create', 'update']],
//            [['fat', 'snf'], 'validateRange', 'on' => ['create']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_milk_dispatch_txn_code' => Yii::t('app', 'Dcs Milk Dispatch Txn Code'),
            'dcs_milk_dispatch_code' => Yii::t('app', 'Dcs Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'nos_of_can' => Yii::t('app', 'Can'),
            'dispatch_qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'avg_fat' => Yii::t('app', 'FAT'),
            'avg_snf' => Yii::t('app', 'SNF'),
            'avg_clr' => Yii::t('app', 'CLR'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'water' => Yii::t('app', 'Water'),
            'temperature' => Yii::t('app', 'Temperature'),
            'dcs_code' => Yii::t('app', 'Code'),
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
            'total_amount' => Yii::t('app', 'Total Amount'),
        ];
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getDcsMilkDispatch() {
        return $this->hasOne(TblDcsMilkDispatch::className(), ['dcs_milk_dispatch_code' => 'dcs_milk_dispatch_code']);
    }

    public function getApprovalData() {
        return $this->hasOne(TblCollectionDataAlias::className(), ['dcs_code' => 'dcs_code', 'old_milk_type_code' => 'milk_type_code', 'old_milk_quality_type_code' => 'milk_quality_type_code'])->andOnCondition(['tbl_collection_data_alias.table_name' => 'tbl_dcs_milk_dispatch', 'action_perform' => 'DELETE']);
    }

    public function setModelData($model, &$saveModel) {
        $saveModel->dispatch_qty = $model->qty;
        $saveModel->avg_fat = $model->fat;
        $saveModel->avg_snf = $model->snf;
        $saveModel->total_amount = $model->amount;
        $saveModel->avg_clr = $model->clr;
        $saveModel->nos_of_can = $model->no_of_can;
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(dcs_milk_dispatch_txn_code) AS dcs_milk_dispatch_txn_code"])->one();
        return (int) $data['dcs_milk_dispatch_txn_code'] + 1;
    }

    public function getTxnExistingCollection($data) {
        return $this->find()->where(['dcs_milk_dispatch_code' => $this->dcs_milk_dispatch_code, 'dcs_code' => $data->dcs_code, 'milk_type_code' => $data->old_milk_type_code, 'dispatch_qty' => $data->old_qty, 'avg_fat' => $data->old_fat, 'avg_snf' => $data->old_snf])->one();
    }

}
