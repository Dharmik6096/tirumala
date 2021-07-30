<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_penalty_rate_applicability_history".
 *
 * @property integer $id
 * @property string $penalty_rate_applicability_code
 * @property string $penalty_rate_code
 * @property string $penalty_rate
 * @property string $penalty_type
 * @property string $wef_date
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
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
class TblCollectionPenaltyRateApplicabilityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_collection_penalty_rate_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penalty_rate'], 'number'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['penalty_rate_applicability_code'], 'string', 'max' => 35],
            [['penalty_rate_code'], 'string', 'max' => 30],
            [['penalty_type', 'created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['applicable_code'], 'string', 'max' => 15],
            [['applicable_for', 'applicable_type'], 'string', 'max' => 20],
            [['bmc_code'], 'string', 'max' => 12],
            [['mcc_plant_code', 'plant_code'], 'string', 'max' => 6],
            [['union_code'], 'string', 'max' => 3],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['operation_type'], 'string', 'max' => 10],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'penalty_rate_applicability_code' => Yii::t('app', 'Penalty Rate Applicability Code'),
            'penalty_rate_code' => Yii::t('app', 'Penalty Rate Code'),
            'penalty_rate' => Yii::t('app', 'Penalty Rate'),
            'penalty_type' => Yii::t('app', 'Penalty Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
