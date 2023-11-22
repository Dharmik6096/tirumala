<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_bill_head_criteria_slabs_history".
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
class TblMccBillHeadCriteriaSlabsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_criteria_slabs_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['criteria_slab_code', 'criteria_code', 'mcc_bill_head_code', 'for_what', 'originating_org_code', 'history_created_at'], 'safe'],
            [['from_val', 'to_val', 'general_formula_code', 'formula_with_val', 'union_code', 'originating_org_type', 'history_created_by'], 'safe'],
            [['created_at', 'created_by','updated_at', 'updated_by','history_created_at', 'originating_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'criteria_slab_code' => Yii::t('app', 'Criteria Slab Code'),
            'criteria_code' => Yii::t('app', 'Vsp Criteria Code'),
            'mcc_bill_head_code' => Yii::t('app', 'Mcc Bill Head Code'),
            'from_val' => Yii::t('app', 'From Val'),
            'to_val' => Yii::t('app', 'To Val'),
            'general_formula_code' => Yii::t('app', 'Formula'),
            'formula_with_val' => Yii::t('app', 'Formula Val.'),
            'for_what' => Yii::t('app', 'For What'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
