<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_milk_collection_config".
 *
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
 * @property integer $quality_setting
 * @property integer $dispatch_setting
 *
 * @property TblUnions $unionCode
 */
class TblMilkCollectionConfig extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['can_per_ltr', 'default_snf', 'collection_mode', 'weight_setting', 'collection_quantity_mode', 'bmc_collection_quantity_mode', 'local_milk_sale_quantity_mode', 'sample_milk_quantity_mode', 'based_on', 'based_on_disp', 'based_on_local_sale', 'shift_code', 'shift_code_disp', 'quality_setting', 'dispatch_setting'], 'required'],
            [['collection_quantity_mode', 'bmc_collection_quantity_mode', 'local_milk_sale_quantity_mode', 'sample_milk_quantity_mode', 'shift_code', 'shift_code_disp', 'weight_setting'], 'integer'],
            [['variation_in_fat_block', 'variation_in_qty_block', 'variation_in_snf_block'], 'boolean'],
            [['created_at', 'updated_at', 'quality_setting', 'accept_milk', 'multi_entry_diff_milk_type', 'multi_entry_same_milk_type', 'seperate_can', 'no_disp_local_sale', 'no', 'no_disp', 'mcc_plant_code'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['can_warning_per', 'ltr_to_kg', 'default_snf_value', 'lr1_for_clr', 'lr2_for_clr', 'sample_milk_size', 'variation_in_fat', 'variation_in_qty', 'variation_in_snf', 'per_local_sale'], 'double', 'min' => 0],
            [['can_warning_per', 'ltr_to_kg', 'default_snf_value', 'lr1_for_clr', 'lr2_for_clr', 'sample_milk_size', 'variation_in_fat', 'variation_in_qty', 'variation_in_snf', 'per_local_sale'], 'default', 'value' => '0'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['collection_mode'], 'string', 'max' => 25],
            [['based_on', 'based_on_disp'], 'string', 'max' => 255],
            [['based_on_local_sale'], 'string', 'max' => 45],
            [['default_snf_value'], 'validateSnf'],
            [['union_code'], 'configMilkCollection', 'skipOnEmpty' => false, 'on' => 'milkCollection'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'accept_milk' => Yii::t('app', 'Accept milk type other than default milk type of member'),
            'based_on' => Yii::t('app', 'Based On:'),
            'based_on_disp' => Yii::t('app', 'Based On:'),
            'can_per_ltr' => Yii::t('app', 'Ltrs/Can:'),
            'can_warning_per' => Yii::t('app', 'Can filling up warning at:'),
            'collection_mode' => Yii::t('app', 'Collection Mode:'),
            'collection_quantity_mode' => Yii::t('app', 'Member Collection Qty Mode'),
            'bmc_collection_quantity_mode' => Yii::t('app', 'DCS Collection Qty Mode'),
            'local_milk_sale_quantity_mode' => Yii::t('app', 'Local Milk Sale Qty Mode'),
            'sample_milk_quantity_mode' => Yii::t('app', 'Sample Milk Qty Mode'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'default_snf' => Yii::t('app', 'Default SNF'),
            'default_snf_value' => Yii::t('app', 'Default SNF Value:'),
            'from_machine_clr' => Yii::t('app', 'From Machine'),
            'based_on_local_sale' => Yii::t('app', 'Based On:'),
            'no_disp_local_sale' => Yii::t('app', 'No.:'),
            'per_local_sale' => Yii::t('app', 'Percentage'),
            'input_clr' => Yii::t('app', 'Input CLR'),
            'lr1_for_clr' => Yii::t('app', ''),
            'lr2_for_clr' => Yii::t('app', ''),
            'ltr_to_kg' => Yii::t('app', 'Ltr To Kg:'),
            'multi_entry_diff_milk_type' => Yii::t('app', 'Diffrent Milk Type'),
            'multi_entry_same_milk_type' => Yii::t('app', 'Same Milk Type'),
            'no' => Yii::t('app', 'No.:'),
            'no_disp' => Yii::t('app', 'No.:'),
            'sample_milk_size' => Yii::t('app', 'Sample Milk Size(ml.):'),
            'seperate_can' => Yii::t('app', 'Seperate Can for Cow/Buffalo'),
            'shift_code' => Yii::t('app', 'Shift:'),
            'shift_code_disp' => Yii::t('app', 'Shift:'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'variation_in_fat' => Yii::t('app', 'Variation Allowed FAT:'),
            'variation_in_fat_block' => Yii::t('app', 'Block If Exceed'),
            'variation_in_qty' => Yii::t('app', 'Variation Allowed QTY(%):'),
            'variation_in_qty_block' => Yii::t('app', 'Block If Exceed'),
            'variation_in_snf' => Yii::t('app', 'Variation Allowed SNF:'),
            'variation_in_snf_block' => Yii::t('app', 'Block If Exceed'),
            'union_code' => Yii::t('app', 'Union Code'),
            'weight_setting' => Yii::t('app', 'Weight Setting:'),
            'quality_setting' => Yii::t('app', 'Quality Setting:'),
            'dispatch_setting' => Yii::t('app', 'Dispatch Setting:'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function validateSnf($attribute, $params) {
        if ($this->default_snf == 1 && $this->default_snf_value == '') {
            $this->addError($attribute, "Default SNF Value: cannot be blank");
        }
    }

    public function getData() {
        return $this->find()->where(['union_code' => $this->union_code])->one();
    }

    public function configMilkCollection($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('can_per_ltr', 'Something went wrong');
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->dest_org_type = $type;
        $sentbox->source_org_id = $this->union_code;
        return $sentbox;
    }

}
