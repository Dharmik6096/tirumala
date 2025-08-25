<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;

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
class TblSampleBottleTesting extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sample_bottle_testing';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['trip_code', 'sample_no', 'fat', 'snf', 'sample_bottle_testing_date'], 'required'],
            [['sample_no'], 'unique', 'targetAttribute' => ['trip_code', 'sample_no'], 'message' => Yii::t('app/validation', 'Sample No. has been already taken.')],
            [['sample_bottle_testing_code', 'trip_code', 'bmc_milk_dispatch_code', 'bmc_milk_dispatch_txn_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['sample_bottle_testing_date', 'transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'safe'],
            [['fat', 'snf', 'protein'], 'number'],
            [['sample_no'], 'validateSampleNo'],
            [['transaction_date'], 'default', 'value' => date('Y-m-d')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sample_bottle_testing_code' => Yii::t('app', 'Sample Bottle Testing Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'sample_no' => Yii::t('app', 'Sample No.'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'bmc_milk_dispatch_txn_code' => Yii::t('app', 'Bmc Milk Dispatch Txn Code'),
            'sample_bottle_testing_date' => Yii::t('app', 'Testing Date'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'milk_quality_type_code' => Yii::t('app', 'Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'protein' => Yii::t('app', 'Protein'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function validateSampleNo($return = FALSE) {
        $status = 'success';
        $error_msg = '';
        $data = TblBmcMilkDispatch::find()->alias('d')->select(['dtx.milk_type_code', 'dtx.milk_quality_type_code', 'dtx.bmc_milk_dispatch_code', 'dtx.bmc_milk_dispatch_txn_code', 'dtx.union_code', 'dtx.plant_code', 'dtx.mcc_plant_code', 'dtx.bmc_code'])
                ->join('inner join', 'tbl_bmc_milk_dispatch_txn dtx', "dtx.bmc_milk_dispatch_code=d.bmc_milk_dispatch_code")
                ->join('inner join', 'tbl_config_txn_result cr', "cr.ref_code=dtx.bmc_milk_dispatch_txn_code and cr.config_for in ('BMC_DISPATCH','PLANT_DISPATCH')")
                ->join('inner join', 'tbl_config c', "c.config_code=cr.config_code and c.config_key='sample_bottle_no'")
                ->where(['d.trip_code' => $this->trip_code, 'cr.config_result' => $this->sample_no])
                ->asArray()
                ->one();
        $this->attributes = $data;
        if (empty($data)) {
            $status = 'error';
            $error_msg = Yii::t('app/validation', 'Invalid Sample No.');
            $this->addError('sample_no', $error_msg);
        }
        if ($return) {
            return ['status' => $status, 'msg' => $error_msg, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code];
        }
        return;
    }

}
