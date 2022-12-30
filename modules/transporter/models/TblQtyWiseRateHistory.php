<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_qty_wise_rate_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $qty_code
 * @property string $from_qty
 * @property string $to_qty
 * @property string $rate
 * @property string $wef_date
 * @property string $vehicle_code
 * @property string $transporter_code
 * @property string $union_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblQtyWiseRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_qty_wise_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['qty_code'], 'safe'],
                [['rate', 'from_qty', 'to_qty'], 'safe'],
                [['originating_type', 'originating_org_type', 'originating_org_code', 'operation_type', 'wef_date', 'created_at', 'updated_at', 'history_created_at', 'vehicle_code', 'remarks'], 'safe'],
                [['created_by', 'updated_by', 'operation_type', 'transporter_code', 'union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'qty_code' => Yii::t('app', 'Qty Code'),
            'from_qty' => Yii::t('app', 'From Qty'),
            'to_qty' => Yii::t('app', 'To Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'remarks' => Yii::t('app', 'Remarks'),
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
