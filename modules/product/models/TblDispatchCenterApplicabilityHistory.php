<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_dispatch_center_applicability_history".
 *
 * @property integer $id
 * @property string $dispatch_center_applicability_code
 * @property string $dispatch_center_code
 * @property string $dispatch_center_type_code
 * @property string $dispatch_center_name
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property string $mcc_plant_code
 * @property string $dcs_code
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
class TblDispatchCenterApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dispatch_center_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [
                    [
                    'created_at',
                    'updated_at',
                    'history_created_at',
                    'dispatch_center_applicability_code',
                    'originating_type',
                    'dispatch_center_code',
                    'dispatch_center_type_code',
                    'dispatch_center_name',
                    'applicable_code',
                    'applicable_for',
                    'union_code',
                    'mcc_plant_code',
                    'dcs_code',
                    'created_by',
                    'operation_type',
                    'originating_org_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'dispatch_center_applicability_code' => Yii::t('app', 'Dispatch Center Applicability Code'),
            'dispatch_center_code' => Yii::t('app', 'Dispatch Center Code'),
            'dispatch_center_type_code' => Yii::t('app', 'Dispatch Center Type Code'),
            'dispatch_center_name' => Yii::t('app', 'Dispatch Center Name'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
