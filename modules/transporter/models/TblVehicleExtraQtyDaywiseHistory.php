<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_extra_qty_daywise_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $extra_qty_code
 * @property string $additional_qty
 * @property string $deduction_qty
 * @property string $rate
 * @property string $date
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
class TblVehicleExtraQtyDaywiseHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_extra_qty_daywise_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['remarks', 'vehicle_code', 'transporter_code', 'created_by', 'updated_by', 'union_code', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'history_created_at', 'date', 'created_at', 'updated_at', 'rate'], 'safe'],
                [['extra_qty_code', 'originating_type'], 'integer'],
                [['additional_qty', 'deduction_qty', 'rate'], 'number'],
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
            'extra_qty_code' => Yii::t('app', 'Extra Qty Code'),
            'additional_qty' => Yii::t('app', 'Additional Qty'),
            'deduction_qty' => Yii::t('app', 'Deduction Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'date' => Yii::t('app', 'Date'),
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
