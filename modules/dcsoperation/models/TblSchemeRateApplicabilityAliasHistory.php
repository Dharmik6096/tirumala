<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_rate_applicability_alias_history".
 *
 * @property integer $id
 * @property integer $scheme_rate_app_alias_code
 * @property string $scheme_rate_code
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $rtpl
 * @property string $union_code
 * @property string $rate_class
 * @property integer $is_active
 * @property string $error_desc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblSchemeRateApplicabilityAliasHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_scheme_rate_applicability_alias_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_rate_app_alias_code', 'from_shift', 'to_shift', 'is_active', 'originating_type'], 'integer'],
            [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at', 'status', 'is_member_rate', 'description'], 'safe'],
            [['rtpl'], 'number'],
            [['scheme_rate_code', 'applicable_for', 'applicable_code'], 'string', 'max' => 20],
            [['union_code', 'rate_class'], 'string', 'max' => 3],
            [['error_desc'], 'string', 'max' => 500],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['operation_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'scheme_rate_app_alias_code' => Yii::t('app', 'Scheme Rate App Alias Code'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'union_code' => Yii::t('app', 'Union Code'),
            'rate_class' => Yii::t('app', 'Rate Class'),
            'is_active' => Yii::t('app', 'Is Active'),
            'error_desc' => Yii::t('app', 'Error Desc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'is_member_rate' => Yii::t('app', 'Is Member Rate'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
