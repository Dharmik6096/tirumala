<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_medicine_stock_transaction".
 *
 * @property integer $medicine_stock_transaction_id
 * @property integer $medicine_id
 * @property string $union_code
 * @property string $module_name
 * @property string $module_code
 * @property string $batch_no
 * @property string $tran_datetime
 * @property string $old_value
 * @property string $new_value
 * @property string $final_value
 * @property string $expire_date
 * @property string $entry_type
 * @property string $transfer_ref_code
 * @property string $transfer_ref_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMedicineStockTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_medicine_stock_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medicine_id'], 'required'],
            [['medicine_id', 'originating_type'], 'integer'],
            [['tran_datetime', 'expire_date', 'created_at', 'updated_at'], 'safe'],
            [['old_value', 'new_value', 'final_value'], 'number'],
            [['union_code'], 'string', 'max' => 5],
            [['module_name', 'module_code', 'transfer_ref_code', 'transfer_ref_name'], 'string', 'max' => 50],
            [['batch_no'], 'string', 'max' => 500],
            [['entry_type'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medicine_stock_transaction_id' => Yii::t('app', 'Medicine Stock Transaction ID'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'batch_no' => Yii::t('app', 'Batch No'),
            'tran_datetime' => Yii::t('app', 'Tran Datetime'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'final_value' => Yii::t('app', 'Final Value'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'transfer_ref_code' => Yii::t('app', 'Transfer Ref Code'),
            'transfer_ref_name' => Yii::t('app', 'Transfer Ref Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
