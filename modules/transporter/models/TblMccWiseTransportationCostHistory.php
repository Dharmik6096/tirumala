<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_wise_transportation_cost_history".
 *
 * @property integer $id
 * @property integer $tpt_cost_code
 * @property string $union_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $primary_tpt_cost
 * @property string $wef_date
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
class TblMccWiseTransportationCostHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_wise_transportation_cost_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tpt_cost_code', 'originating_type'], 'safe'],
            [['union_code', 'mcc_plant_code', 'plant_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by'], 'safe'],
            [['primary_tpt_cost'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'tpt_cost_code' => Yii::t('app', 'Tpt Cost Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'primary_tpt_cost' => Yii::t('app', 'Primary Tpt Cost'),
            'wef_date' => Yii::t('app', 'Wef Date'),
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
