<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria_applicability_history".
 *
 * @property integer $id
 * @property integer $bill_head_criteria_applicability_code
 * @property string $vsp_criteria_code
 * @property string $wef_date
 * @property string $bill_head_code
 * @property string $union_code
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $bmc_code
 * @property string $bill_head_for
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVspBillHeadCriteriaApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_bill_head_criteria_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_criteria_applicability_code', 'originating_type'], 'integer'],
                [['wef_date', 'created_at', 'updated_at', 'history_created_at', 'from_date', 'to_date'], 'safe'],
                [['vsp_criteria_code', 'applicable_code', 'applicable_for', 'bill_head_for'], 'string', 'max' => 20],
                [['bill_head_code', 'operation_type'], 'string', 'max' => 10],
                [['union_code'], 'string', 'max' => 3],
                [['bmc_code'], 'string', 'max' => 12],
                [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bill_head_criteria_applicability_code' => Yii::t('app', 'Bill Head Criteria Applicability Code'),
            'vsp_criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
