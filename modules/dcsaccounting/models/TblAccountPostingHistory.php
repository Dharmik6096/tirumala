<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_account_posting_history".
 *
 * @property integer $id
 * @property string $account_posting_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $from_shift
 * @property integer $to_shift
 * @property integer $posting_type
 * @property integer $status
 * @property integer $event_type
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAccountPostingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_account_posting_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at', 'from_shift', 'to_shift', 'posting_type', 'status', 'event_type', 'originating_type', 'account_posting_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_posting_code' => Yii::t('app', 'Account Posting Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'posting_type' => Yii::t('app', 'Posting Type'),
            'status' => Yii::t('app', 'Status'),
            'event_type' => Yii::t('app', 'Event Type'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
