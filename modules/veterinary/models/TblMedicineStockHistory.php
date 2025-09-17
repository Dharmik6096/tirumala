<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_medicine_stock_history".
 *
 * @property integer $id
 * @property integer $medicine_stock_id
 * @property integer $medicine_id
 * @property string $union_code
 * @property string $module_name
 * @property string $module_code
 * @property string $stock
 * @property string $batch_no
 * @property string $expire_date
 * @property string $rate
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMedicineStockHistory extends TblMedicineStock {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_stock_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_stock_id', 'medicine_id', 'union_code', 'module_name', 'module_code', 'stock', 'batch_no', 'expire_date', 'rate', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'medicine_stock_id' => Yii::t('app', 'Medicine Stock ID'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'union_code' => Yii::t('app', 'Union'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'stock' => Yii::t('app', 'Stock'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'rate' => Yii::t('app', 'Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
