<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_medicine_stock".
 *
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
 */
class TblMedicineStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_medicine_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medicine_id'], 'required'],
            [['medicine_id', 'originating_type'], 'integer'],
            [['stock', 'rate'], 'number'],
            [['expire_date', 'created_at', 'updated_at'], 'safe'],
            [['union_code'], 'string', 'max' => 5],
            [['module_name', 'module_code'], 'string', 'max' => 50],
            [['batch_no'], 'string', 'max' => 500],
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
            'medicine_stock_id' => Yii::t('app', 'Medicine Stock ID'),
            'medicine_id' => Yii::t('app', 'Medicine ID'),
            'union_code' => Yii::t('app', 'Union Code'),
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
        ];
    }
}
