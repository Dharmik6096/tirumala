<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_competitors_applicability_history".
 *
 * @property integer $id
 * @property integer $competitors_applicability_id
 * @property integer $competitor_id
 * @property string $customer_type
 * @property string $customer_code
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
class TblCompetitorsApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_competitors_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['competitors_applicability_id', 'competitor_id', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type', 'customer_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'competitors_applicability_id' => Yii::t('app', 'Competitors Applicability ID'),
            'competitor_id' => Yii::t('app', 'Competitor ID'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
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
