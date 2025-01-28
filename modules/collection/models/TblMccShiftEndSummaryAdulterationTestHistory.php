<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_shift_end_summary_adulteration_test_history".
 *
 * @property integer $id
 * @property integer $mcc_shift_end_summary_adulteration_test_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property string $quantity
 * @property string $fat
 * @property string $snf
 * @property string $temperature
 * @property integer $taste
 * @property integer $alcohol
 * @property integer $cob
 * @property integer $glucose
 * @property integer $salt
 * @property integer $sugar
 * @property integer $urea
 * @property integer $starch
 * @property integer $rosolic_acid
 * @property integer $h2o2
 * @property integer $formalin
 * @property integer $detergent
 * @property integer $nitrate_comp
 * @property integer $ammonium_comp
 * @property string $acidity
 * @property string $mbrt
 * @property string $malto_dextrin
 * @property string $protein_chainna
 * @property string $br_value
 * @property string $rm_value
 * @property string $remarks
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMccShiftEndSummaryAdulterationTestHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mcc_shift_end_summary_adulteration_test_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_shift_end_summary_adulteration_test_code','date_time_of_collection','shift_code','milk_type_code','quantity','fat','snf','temperature','taste','alcohol','cob','glucose','salt','sugar','urea','starch','rosolic_acid','h2o2','formalin','detergent','nitrate_comp','ammonium_comp','acidity','mbrt','malto_dextrin','protein_chainna','br_value','rm_value','remarks','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','created_at','created_by','updated_at','updated_by','flg_sentbox_entry','sync_status','sync_timestamp','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','x_col1','x_col2','x_col3','x_col4','x_col5','operation_type'], 'safe'],
            [['union_code'], 'required', 'on' => ['androidsync']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mcc_shift_end_summary_adulteration_test_code' => Yii::t('app', 'Mcc Shift End Summary Adulteration Test Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'temperature' => Yii::t('app', 'Temperature'),
            'taste' => Yii::t('app', 'Taste'),
            'alcohol' => Yii::t('app', 'Alcohol'),
            'cob' => Yii::t('app', 'Cob'),
            'glucose' => Yii::t('app', 'Glucose'),
            'salt' => Yii::t('app', 'Salt'),
            'sugar' => Yii::t('app', 'Sugar'),
            'urea' => Yii::t('app', 'Urea'),
            'starch' => Yii::t('app', 'Starch'),
            'rosolic_acid' => Yii::t('app', 'Rosolic Acid'),
            'h2o2' => Yii::t('app', 'H2o2'),
            'formalin' => Yii::t('app', 'Formalin'),
            'detergent' => Yii::t('app', 'Detergent'),
            'nitrate_comp' => Yii::t('app', 'Nitrate Comp'),
            'ammonium_comp' => Yii::t('app', 'Ammonium Comp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'malto_dextrin' => Yii::t('app', 'Malto Dextrin'),
            'protein_chainna' => Yii::t('app', 'Protein Chainna'),
            'br_value' => Yii::t('app', 'Br Value'),
            'rm_value' => Yii::t('app', 'Rm Value'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
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
