<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_medicine_stock_transfer".
 *
 * @property integer $medicine_stock_transfer_code
 * @property string $union_code
 * @property string $transaction_date
 * @property string $from_type
 * @property string $from_code
 * @property string $to_type
 * @property string $to_code
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
class TblMedicineStockTransfer extends ChildModel {

    public $from_user_code, $to_user_code, $medicine_wise;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_medicine_stock_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'transaction_date', 'from_type', 'from_code', 'to_type', 'to_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_user_code', 'to_user_code', 'medicine_wise'], 'safe'],
            [['remarks'], 'string'],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['from_type', 'from_code', 'to_type', 'to_code'], 'string', 'max' => 50],
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
            'medicine_stock_transfer_code' => Yii::t('app', 'Medicine Stock Transfer Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_code' => Yii::t('app', 'From Code'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_code' => Yii::t('app', 'To Code'),
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

}
