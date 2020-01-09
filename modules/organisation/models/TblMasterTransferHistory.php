<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_master_transfer_history".
 *
 * @property integer $id
 * @property integer $master_transfer_code
 * @property string $master_type
 * @property string $transfer_type
 * @property string $old_member_code
 * @property string $new_member_code
 * @property string $old_dcs_code
 * @property string $new_dcs_code
 * @property string $old_bmc_code
 * @property string $new_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $new_mcc_plant_code
 * @property string $old_route_code
 * @property string $new_route_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $wef_date
 * @property integer $status
 * @property integer $update_transaction
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMasterTransferHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_master_transfer_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['master_transfer_code', 'status', 'update_transaction'], 'safe'],
            [['master_type', 'transfer_type', 'old_member_code', 'new_member_code', 'old_dcs_code', 'new_dcs_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'old_route_code', 'new_route_code', 'plant_code', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['wef_date', 'pick_datetime', 'response_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'master_transfer_code' => Yii::t('app', 'Master Transfer Code'),
            'master_type' => Yii::t('app', 'Master Type'),
            'transfer_type' => Yii::t('app', 'Transfer Type'),
            'old_member_code' => Yii::t('app', 'Old Member Code'),
            'new_member_code' => Yii::t('app', 'New Member Code'),
            'old_dcs_code' => Yii::t('app', 'Old Dcs Code'),
            'new_dcs_code' => Yii::t('app', 'New Dcs Code'),
            'old_bmc_code' => Yii::t('app', 'Old Bmc Code'),
            'new_bmc_code' => Yii::t('app', 'New Bmc Code'),
            'old_mcc_plant_code' => Yii::t('app', 'Old Mcc Plant Code'),
            'new_mcc_plant_code' => Yii::t('app', 'New Mcc Plant Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
            'new_route_code' => Yii::t('app', 'New Route Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'status' => Yii::t('app', 'Status'),
            'update_transaction' => Yii::t('app', 'Update Transaction'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
