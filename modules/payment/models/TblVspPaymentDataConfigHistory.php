<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_payment_data_config_history".
 *
 * @property integer $id
 * @property integer $vsp_payment_data_config_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
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
class TblVspPaymentDataConfigHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_payment_data_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vsp_payment_data_config_code', 'shift_code', 'originating_type', 'union_code', 'date_time_of_collection', 'created_at', 'updated_at', 'history_created_at', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'created_by', 'updated_by', 'originating_org_code', 'history_created_by', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vsp_payment_data_config_code' => Yii::t('app', 'Vsp Payment Data Config Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
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
