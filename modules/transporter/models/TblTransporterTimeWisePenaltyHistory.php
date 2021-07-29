<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_transporter_time_wise_penalty_history".
 *
 * @property integer $id
 * @property integer $penalty_code
 * @property string $union_code
 * @property string $penalty_amount
 * @property string $minute_limit
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblTransporterTimeWisePenaltyHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_time_wise_penalty_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_code', 'originating_type', 'bmc_code'], 'safe'],
            [['union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'history_created_by'], 'safe'],
            [['penalty_amount', 'minute_limit'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'operation_type', 'mcc_plant_code', 'plant_code', 'wef_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'penalty_code' => Yii::t('app', 'Penalty Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'penalty_amount' => Yii::t('app', 'Penalty Amount'),
            'minute_limit' => Yii::t('app', 'Minute Limit'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
