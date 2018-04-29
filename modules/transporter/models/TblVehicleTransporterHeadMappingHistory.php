<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_transporter_head_mapping_history".
 *
 * @property integer $id
 * @property integer $vehicle_transporter_head_mapping_code
 * @property integer $transporter_payment_head_code
 * @property string $vehicle_code
 * @property string $wef_date
 * @property string $amount
 * @property string $remarks
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblVehicleTransporterHeadMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_transporter_head_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_transporter_head_mapping_code'], 'safe'],
            [['vehicle_transporter_head_mapping_code', 'transporter_payment_head_code', 'is_active'], 'safe'],
            [['vehicle_code', 'remarks', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vehicle_transporter_head_mapping_code' => Yii::t('app', 'Vehicle Transporter Head Mapping Code'),
            'transporter_payment_head_code' => Yii::t('app', 'Transporter Payment Head Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'amount' => Yii::t('app', 'Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
