<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_transfer_history".
 *
 * @property integer $id
 * @property string $milk_transfer_code
 * @property string $transaction_id
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property integer $transfer_type
 * @property string $union_code
 * @property string $source_code
 * @property string $destination_code
 * @property string $vehicle_no
 * @property string $fat
 * @property string $snf
 * @property string $qty
 * @property string $temp
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMilkTransferHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_transfer_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_transfer_code', 'source_type', 'destination_type', 'transaction_datetime', 'shift_code', 'is_rechilling'], 'safe'],
            [['from_date', 'to_date', 'transaction_id', 'union_code', 'source_code', 'destination_code', 'vehicle_no'], 'safe'],
            [['from_shift', 'to_shift', 'transfer_type', 'originating_type'], 'safe'],
            [['fat', 'snf', 'qty', 'temp'], 'safe'],
            [['remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type', 'history_created_by', 'history_created_at'], 'safe'],
            [['conductivity', 'ph_value', 'other_reading', 'freezing_point', 'salt', 'adt_value', 'adt_param', 'lactose', 'density', 'protein', 'water', 'clr'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_transfer_code' => Yii::t('app', 'Milk Transfer Code'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'transfer_type' => Yii::t('app', 'Transfer Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'source_code' => Yii::t('app', 'Source Code'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'qty' => Yii::t('app', 'Qty'),
            'temp' => Yii::t('app', 'Temp'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'transaction_datetime' => Yii::t('app', 'Transaction Date'),
            'shift_code' => Yii::t('app', 'Shift'),
        ];
    }

}
