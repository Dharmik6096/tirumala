<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_config_history".
 *
 * @property integer $id
 * @property integer $code
 * @property integer $accept_milk
 * @property string $based_on
 * @property string $based_on_disp
 * @property integer $can_per_ltr
 * @property string $can_warning_per
 * @property string $collection_mode
 * @property integer $collection_quantity_mode
 * @property integer $bmc_collection_quantity_mode
 * @property integer $local_milk_sale_quantity_mode
 * @property integer $sample_milk_quantity_mode
 * @property string $created_at
 * @property string $created_by
 * @property integer $default_snf
 * @property string $default_snf_value
 * @property integer $from_machine_clr
 * @property string $based_on_local_sale
 * @property integer $no_disp_local_sale
 * @property integer $per_local_sale
 * @property integer $input_clr
 * @property string $lr1_for_clr
 * @property string $lr2_for_clr
 * @property string $ltr_to_kg
 * @property integer $multi_entry_diff_milk_type
 * @property integer $multi_entry_same_milk_type
 * @property integer $no
 * @property integer $no_disp
 * @property string $sample_milk_size
 * @property integer $seperate_can
 * @property integer $shift_code
 * @property integer $shift_code_disp
 * @property string $updated_at
 * @property string $updated_by
 * @property string $variation_in_fat
 * @property integer $variation_in_fat_block
 * @property string $variation_in_qty
 * @property integer $variation_in_qty_block
 * @property string $variation_in_snf
 * @property integer $variation_in_snf_block
 * @property string $union_code
 * @property integer $weight_setting
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblMilkCollectionConfigHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'accept_milk', 'can_per_ltr', 'collection_quantity_mode', 'bmc_collection_quantity_mode', 'local_milk_sale_quantity_mode', 'sample_milk_quantity_mode', 'default_snf', 'from_machine_clr', 'no_disp_local_sale', 'per_local_sale', 'input_clr', 'multi_entry_diff_milk_type', 'multi_entry_same_milk_type', 'no', 'no_disp', 'seperate_can', 'shift_code', 'shift_code_disp', 'variation_in_fat_block', 'variation_in_qty_block', 'variation_in_snf_block', 'weight_setting', 'quality_setting', 'dispatch_setting'], 'safe'],
            [['based_on', 'based_on_disp', 'collection_mode', 'created_by', 'based_on_local_sale', 'updated_by', 'union_code', 'operation_type', 'history_created_by'], 'safe'],
            [['can_warning_per', 'default_snf_value', 'lr1_for_clr', 'lr2_for_clr', 'ltr_to_kg', 'sample_milk_size', 'variation_in_fat', 'variation_in_qty', 'variation_in_snf'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'mcc_plant_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'accept_milk' => 'Accept Milk',
            'based_on' => 'Based On',
            'based_on_disp' => 'Based On Disp',
            'can_per_ltr' => 'Can Per Ltr',
            'can_warning_per' => 'Can Warning Per',
            'collection_mode' => 'Collection Mode',
            'collection_quantity_mode' => 'Collection Quantity Mode',
            'bmc_collection_quantity_mode' => 'Bmc Collection Quantity Mode',
            'local_milk_sale_quantity_mode' => 'Local Milk Sale Quantity Mode',
            'sample_milk_quantity_mode' => 'Sample Milk Quantity Mode',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'default_snf' => 'Default Snf',
            'default_snf_value' => 'Default Snf Value',
            'from_machine_clr' => 'From Machine Clr',
            'based_on_local_sale' => 'Based On Local Sale',
            'no_disp_local_sale' => 'No Disp Local Sale',
            'per_local_sale' => 'Per Local Sale',
            'input_clr' => 'Input Clr',
            'lr1_for_clr' => 'Lr1 For Clr',
            'lr2_for_clr' => 'Lr2 For Clr',
            'ltr_to_kg' => 'Ltr To Kg',
            'multi_entry_diff_milk_type' => 'Multi Entry Diff Milk Type',
            'multi_entry_same_milk_type' => 'Multi Entry Same Milk Type',
            'no' => 'No',
            'no_disp' => 'No Disp',
            'sample_milk_size' => 'Sample Milk Size',
            'seperate_can' => 'Seperate Can',
            'shift_code' => 'Shift Code',
            'shift_code_disp' => 'Shift Code Disp',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'variation_in_fat' => 'Variation In Fat',
            'variation_in_fat_block' => 'Variation In Fat Block',
            'variation_in_qty' => 'Variation In Qty',
            'variation_in_qty_block' => 'Variation In Qty Block',
            'variation_in_snf' => 'Variation In Snf',
            'variation_in_snf_block' => 'Variation In Snf Block',
            'union_code' => 'Union Code',
            'weight_setting' => 'Weight Setting',
            'history_created_at' => 'History Created At',
            'operation_type' => 'Operation Type',
            'history_created_by' => 'History Created By',
        ];
    }

}
