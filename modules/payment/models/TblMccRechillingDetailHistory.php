<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_rechilling_detail_history".
 *
 * @property integer $id
 * @property integer $mcc_rechilling_detail_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property integer $chiller_info_code
 * @property string $chilling_date
 * @property integer $shift_code
 * @property string $qty
 * @property string $rate
 * @property string $amount
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblMccRechillingDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_rechilling_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_rechilling_detail_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'chiller_info_code', 'chilling_date', 'shift_code', 'qty', 'rate', 'amount', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'mcc_rechilling_detail_code' => Yii::t('app', 'Mcc Rechilling Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'chiller_info_code' => Yii::t('app', 'Chiller Info Code'),
            'chilling_date' => Yii::t('app', 'Chilling Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'qty' => Yii::t('app', 'Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
