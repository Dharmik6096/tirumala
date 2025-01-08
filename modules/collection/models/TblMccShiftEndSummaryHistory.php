<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_shift_end_summary_history".
 *
 * @property integer $id
 * @property integer $mcc_shift_end_summary_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property string $quantity
 * @property string $fat
 * @property string $snf
 * @property string $p_quantity
 * @property string $p_fat
 * @property string $p_snf
 * @property string $d_quantity
 * @property string $d_fat
 * @property string $d_snf
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
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
class TblMccShiftEndSummaryHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mcc_shift_end_summary_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_shift_end_summary_code','date_time_of_collection','shift_code','milk_type_code','quantity','fat','snf','p_quantity','p_fat','p_snf','d_quantity','d_fat','d_snf','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','flg_sentbox_entry','sync_status','sync_timestamp','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','x_col1','x_col2','x_col3','x_col4','x_col5','operation_type'], 'safe'],
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
            'mcc_shift_end_summary_code' => Yii::t('app', 'Mcc Shift End Summary Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'p_quantity' => Yii::t('app', 'P Quantity'),
            'p_fat' => Yii::t('app', 'P Fat'),
            'p_snf' => Yii::t('app', 'P Snf'),
            'd_quantity' => Yii::t('app', 'D Quantity'),
            'd_fat' => Yii::t('app', 'D Fat'),
            'd_snf' => Yii::t('app', 'D Snf'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
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
