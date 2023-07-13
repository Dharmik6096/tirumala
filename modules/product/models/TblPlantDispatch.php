<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\product\models\TblProduct;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_plant_dispatch".
 *
 * @property string $plant_dispatch_code
 * @property string $dispatch_date
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $document_date
 * @property string $document_no
 * @property string $status
 * @property string $remarks
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
class TblPlantDispatch extends \app\models\ChildModel {

    public $product_code, $rate, $qty, $sap_batch_no, $lr_no;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['plant_dispatch_code', 'union_code'], 'required', 'except' => ['importCsv']],
                [['product_code', 'rate', 'qty', 'sap_batch_no', 'lr_no'], 'safe'],
                [['mcc_plant_code', 'plant_code', 'document_no', 'document_date', 'dispatch_date'], 'required'],
                [['product_code', 'rate', 'qty', 'sap_batch_no'], 'required', 'on' => ['importCsv']],
                [['dispatch_date', 'document_date', 'created_at', 'updated_at', 'remarks'], 'safe'],
                [['plant_dispatch_code', 'union_code', 'mcc_plant_code', 'plant_code', 'document_no'], 'safe'],
                [['originating_type', 'status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['status'], 'default', 'value' => '0'],
                [['document_no'], 'unique', 'except' => ['importCsv']],
                [['dispatch_date', 'document_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['dispatch_date', 'document_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['dispatch_date', 'document_date'], 'convertDate', 'on' => ['importCsv']],
                [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code'], 'on' => 'importCsv'],
                [['mcc_plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMccPlant::className(), 'targetAttribute' => ['mcc_plant_code' => 'mcc_plant_code'], 'on' => 'importCsv'],
                [['product_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProduct::className(), 'targetAttribute' => ['product_code' => 'product_code'], 'on' => 'importCsv'],
                [['mcc_plant_code'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'plant_dispatch_code' => Yii::t('app', 'Plant Dispatch Code'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'document_date' => Yii::t('app', 'Document Date'),
            'document_no' => Yii::t('app', 'Document No'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
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

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function setChildTable(&$model, &$saveModel, &$errors) {
        if (!empty($model->document_no)) {
            $existBatch = $model->find()->where(['document_no' => $model->document_no])->one();
            if (!empty($existBatch)) {
                if ($existBatch->plant_code != $model->plant_code || $existBatch->mcc_plant_code != $model->mcc_plant_code) {
                    $model->addError('document_no', Yii::t('app/validation', $this->getAttributeLabel('document_no') . ' is already available in another Dispatch'));
                } else {
                    $txn_model = new TblPlantDispatchTxn();
                    $txn_model->attributes = $model->attributes;
                    $txn_model->plant_dispatch_txn_code = Yii::$app->general->getCodeAutoIncrement($txn_model, 1);
                    $txn_model->product_code = $model->product_code;
                    $txn_model->union_code = $model->union_code;
                    $txn_model->plant_dispatch_code = $existBatch->plant_dispatch_code;
                    $txn_model->unit_code = Yii::$app->general->getforeignkey($this->productCode, 'unit_code');
                    $txn_model->rate = $model->rate;
                    $txn_model->sap_batch_no = $model->sap_batch_no;
                    $txn_model->qty = $model->qty;
                    $txn_model->amount = ($txn_model->qty) * $txn_model->rate;
                    $txn_model->grn_missing_qty = $txn_model->qty;
                    if (!$txn_model->validate()) {
                        $errors[] = $txn_model->getErrors();
                    }
                    if (empty($txn_model->getErrors()) && $txn_model->validate()) {
                        $model = $txn_model;
                    }
                }
            } else {
                $txn_model = new TblPlantDispatchTxn();
                $txn_model->attributes = $model->attributes;
                $txn_model->plant_dispatch_txn_code = Yii::$app->general->getCodeAutoIncrement($txn_model, 1);
                $txn_model->product_code = $model->product_code;
                $txn_model->union_code = $model->union_code;
                $txn_model->unit_code = Yii::$app->general->getforeignkey($this->productCode, 'unit_code');
                $txn_model->rate = $model->rate;
                $txn_model->sap_batch_no = $model->sap_batch_no;
                $txn_model->qty = $model->qty;
                $txn_model->amount = ($txn_model->qty) * $txn_model->rate;
                $txn_model->grn_missing_qty = $txn_model->qty;
                if (!$txn_model->validate()) {
                    $errors[] = $txn_model->getErrors();
                }
                if (empty($txn_model->getErrors()) && $txn_model->validate()) {
                    array_push($saveModel, $txn_model);
                }
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->dispatch_date = Yii::$app->controls->view_date($this->dispatch_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->dispatch_date = '-';
        }
        try {
            $this->document_date = Yii::$app->controls->view_date($this->document_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->document_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->dispatch_date = !empty($this->dispatch_date) ? Yii::$app->controls->view_date($this->dispatch_date, 'php:Y-m-d') : NULL;
            $this->document_date = !empty($this->document_date) ? Yii::$app->controls->view_date($this->document_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setImport($attribute, $params) {
        $this->union_code = Yii::$app->general->getforeignkey($this->mccPlantCode, 'union_code');
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
