<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria_history".
 *
 * @property integer $id
 * @property string $vsp_criteria_code
 * @property string $criteria_name
 * @property integer $bill_head_code
 * @property string $general_formula_code
 * @property string $union_code
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
class TblVspBillHeadCriteriaHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vsp_bill_head_criteria_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_head_code', 'originating_type'], 'integer'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['vsp_criteria_code', 'general_formula_code'], 'string', 'max' => 20],
            [['criteria_name'], 'string', 'max' => 100],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 255],
            [['history_created_by'], 'string', 'max' => 14],
            [['operation_type'], 'string', 'max' => 10],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vsp_criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'criteria_name' => Yii::t('app', 'Criteria Name'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'general_formula_code' => Yii::t('app', 'General Formula Code'),
            'union_code' => Yii::t('app', 'Union Code'),
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
