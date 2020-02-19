<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_applicability_history".
 *
 * @property integer $id
 * @property integer $bill_head_applicabilty_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property string $bill_head_code
 * @property string $union_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblBillHeadApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_applicabilty_code'], 'safe'],
            [['created_at', 'updated_at', 'wef_date', 'history_created_at'], 'safe'],
            [['created_by', 'updated_by', 'dcs_code', 'bill_head_code', 'union_code', 'operation_type', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['applicable_code', 'applicable_for'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bill_head_applicabilty_code' => Yii::t('app', 'Bill Head Applicabilty Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
