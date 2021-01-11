<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_bill_head_criteria_slabs_history".
 *
 * @property integer $id
 * @property string $vsp_slab_code
 * @property string $vsp_criteria_code
 * @property integer $bill_head_code
 * @property string $from_val
 * @property string $to_val
 * @property string $general_formula_code
 * @property string $formula_with_val
 * @property string $for_what
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
class TblVspBillHeadCriteriaSlabsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_bill_head_criteria_slabs_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_code', 'originating_type'], 'safe'],
            [['from_val', 'to_val'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['vsp_slab_code', 'vsp_criteria_code', 'general_formula_code', 'for_what'], 'safe'],
            [['formula_with_val'], 'safe'],
            [['union_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['history_created_by'], 'safe'],
            [['operation_type'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vsp_slab_code' => Yii::t('app', 'Vsp Slab Code'),
            'vsp_criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'from_val' => Yii::t('app', 'From Val'),
            'to_val' => Yii::t('app', 'To Val'),
            'general_formula_code' => Yii::t('app', 'General Formula Code'),
            'formula_with_val' => Yii::t('app', 'Formula With Val'),
            'for_what' => Yii::t('app', 'For What'),
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
