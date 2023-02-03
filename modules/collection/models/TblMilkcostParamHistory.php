<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milkcost_param_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $milkcost_param_code
 * @property string $chilling_rate
 * @property string $primary_tpt_cost
 * @property string $commission_percentage
 * @property string $labour_charge
 * @property string $service_charge
 * @property string $wef_date
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
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
 */
class TblMilkcostParamHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milkcost_param_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'wef_date', 'created_at', 'updated_at', 'headload_charge', 'building_rent_labour_charge', 'dgset_service_other_charge', 'other_charge_addition', 'other_charge_deduction', 'shift_code'], 'safe'],
                [['milkcost_param_code', 'originating_type'], 'safe'],
                [['chilling_rate', 'primary_tpt_cost', 'commission_percentage', 'labour_charge', 'service_charge'], 'safe'],
                [['operation_type'], 'safe'],
                [['history_created_by', 'created_by', 'updated_by'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'milkcost_param_code' => Yii::t('app', 'Milkcost Param Code'),
            'chilling_rate' => Yii::t('app', 'Chilling Rate'),
            'primary_tpt_cost' => Yii::t('app', 'Primary Tpt Cost'),
            'commission_percentage' => Yii::t('app', 'Commission Percentage'),
            'labour_charge' => Yii::t('app', 'Labour Charge'),
            'service_charge' => Yii::t('app', 'Service Charge'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
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
        ];
    }

}
