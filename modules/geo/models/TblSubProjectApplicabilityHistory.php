<?php

namespace app\modules\geo\models;

use Yii;

/**
 * This is the model class for table "tbl_sub_project_applicability_history".
 *
 * @property integer $id
 * @property integer $sub_project_applicability_code
 * @property integer $sub_project_code
 * @property string $union_code
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $wef_date
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSubProjectApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sub_project_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sub_project_applicability_code', 'sub_project_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'applicable_for', 'applicable_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'history_created_at', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['sub_project_applicability_code', 'sub_project_code', 'originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['applicable_for'], 'string', 'max' => 20],
            [['applicable_code'], 'string', 'max' => 15],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['operation_type'], 'string', 'max' => 10],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'sub_project_applicability_code' => Yii::t('app', 'Sub Project Applicability Code'),
            'sub_project_code' => Yii::t('app', 'Sub Project  Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
