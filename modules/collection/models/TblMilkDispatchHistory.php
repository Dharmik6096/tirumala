<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_dispatch_history".
 *
 * @property integer $id
 * @property string $milk_dispatch_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $shift
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $history_created_at
 */
class TblMilkDispatchHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_dispatch_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id'], 'required'],
//            [['id', 'milk_type_code', 'sample_no'], 'integer'],
//            [['milk_dispatch_code', 'dcs_code', 'bmc_code', 'shift', 'village_code'], 'string'],
//            [['fat', 'snf', 'water', 'qty'], 'number'],
            [['id', 'milk_type_code', 'sample_no', 'milk_dispatch_code', 'dcs_code', 'bmc_code', 'shift', 'village_code', 'fat', 'snf', 'water', 'qty', 'date_time_of_collection', 'date_time_of_recieve', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        /*return [
            'id' => Yii::t('app', 'ID'),
            'milk_dispatch_code' => Yii::t('app', 'Milk Dispatch Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'shift' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];*/
    }

    /**
     * @inheritdoc
     * @return TblMilkDispatchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkDispatchQuery(get_called_class());
    }
}
