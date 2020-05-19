<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_sample_bottle_testing".
 *
 * @property string $sample_bottle_testing_code
 * @property string $trip_code
 * @property string $bmc_milk_dispatch_code
 * @property string $bmc_milk_dispatch_txn_code
 * @property string $sample_bottle_testing_date
 * @property string $transaction_date
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $protein
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
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
class TblSampleBottleTesting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sample_bottle_testing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sample_bottle_testing_code'], 'required'],
            [['sample_bottle_testing_code', 'trip_code', 'bmc_milk_dispatch_code', 'bmc_milk_dispatch_txn_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['sample_bottle_testing_date', 'transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['fat', 'snf', 'protein'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sample_bottle_testing_code' => Yii::t('app', 'Sample Bottle Testing Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'bmc_milk_dispatch_txn_code' => Yii::t('app', 'Bmc Milk Dispatch Txn Code'),
            'sample_bottle_testing_date' => Yii::t('app', 'Sample Bottle Testing Date'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'protein' => Yii::t('app', 'Protein'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
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
