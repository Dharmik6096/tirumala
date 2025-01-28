<?php

namespace app\modules\insurance\models;

use Yii;

/**
 * This is the model class for table "tbl_insurance_master_history".
 *
 * @property integer $id
 * @property integer $insurance_master_code
 * @property string $insurance_start_date
 * @property string $insurance_end_date
 * @property string $dcs_edit_start_date
 * @property string $dcs_edit_end_date
 * @property integer $member_min_age
 * @property integer $member_max_age
 * @property string $insurance_final_date
 * @property string $insurance_description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInsuranceMasterHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_insurance_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'insurance_master_code', 'union_code', 'insurance_start_date', 'insurance_end_date', 'dcs_edit_start_date', 'dcs_edit_end_date', 'member_min_age', 'member_max_age', 'insurance_final_date', 'insurance_description', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'history_created_at', 'history_created_by', 'operation_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'insurance_master_code' => Yii::t('app', 'Insurance Master Code'),
            'insurance_start_date' => Yii::t('app', 'Insurance Start Date'),
            'insurance_end_date' => Yii::t('app', 'Insurance End Date'),
            'dcs_edit_start_date' => Yii::t('app', 'Dcs Edit Start Date'),
            'dcs_edit_end_date' => Yii::t('app', 'Dcs Edit End Date'),
            'member_min_age' => Yii::t('app', 'Member Min Age'),
            'member_max_age' => Yii::t('app', 'Member Max Age'),
            'insurance_final_date' => Yii::t('app', 'Insurance Final Date'),
            'insurance_description' => Yii::t('app', 'Insurance Description'),
            'is_active' => Yii::t('app', 'Is Active'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
