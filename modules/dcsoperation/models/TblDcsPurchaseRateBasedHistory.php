<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_based_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property integer $deduction_type
 * @property string $deleted_at
 * @property string $deleted_by
 * @property double $end_range
 * @property double $fixed_point
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property boolean $is_delete
 * @property double $kg_rate
 * @property string $operation_type
 * @property string $rate_based_code
 * @property integer $ref_type
 * @property double $start_range
 * @property integer $step
 * @property string $sync_status
 * @property string $sync_time_stamp
 * @property string $updated_at
 * @property string $updated_by
 * @property double $value
 * @property string $formula_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $purchase_rate_code
 * @property integer $quality_param_code
 * @property integer $rate_type
 */
class TblDcsPurchaseRateBasedHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_purchase_rate_based_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'history_created_at', 'sync_time_stamp', 'updated_at'], 'safe'],
            [['deduction_type', 'ref_type', 'step', 'milk_quality_type_code', 'milk_type_code', 'quality_param_code', 'rate_type'], 'integer'],
            [['end_range', 'fixed_point', 'kg_rate', 'start_range', 'value'], 'number'],
            [['is_delete'], 'boolean'],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['operation_type'], 'string', 'max' => 10],
            [['rate_based_code'], 'string', 'max' => 20],
            [['formula_code'], 'string', 'max' => 30],
            [['purchase_rate_code'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'deduction_type' => 'Deduction Type',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'end_range' => 'End Range',
            'fixed_point' => 'Fixed Point',
            'flg_sentbox_entry' => 'Flg Sentbox Entry',
            'history_created_at' => 'History Created At',
            'is_delete' => 'Is Delete',
            'kg_rate' => 'Kg Rate',
            'operation_type' => 'Operation Type',
            'rate_based_code' => 'Rate Based Code',
            'ref_type' => 'Ref Type',
            'start_range' => 'Start Range',
            'step' => 'Step',
            'sync_status' => 'Sync Status',
            'sync_time_stamp' => 'Sync Time Stamp',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'value' => 'Value',
            'formula_code' => 'Formula Code',
            'milk_quality_type_code' => 'Milk Quality Type Code',
            'milk_type_code' => 'Milk Type Code',
            'purchase_rate_code' => 'Purchase Rate Code',
            'quality_param_code' => 'Quality Param Code',
            'rate_type' => 'Rate Type',
        ];
    }
}
