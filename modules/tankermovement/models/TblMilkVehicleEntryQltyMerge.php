<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\organisation\models\TblVehicleMaster;
use app\modules\globalmaster\models\TblVehicleType;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_qlty_merge".
 *
 * @property integer $milk_vehicle_entry_qlty_merge_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $chamber_no
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property integer $is_qty_only
 * @property integer $is_pending_merge
 * @property integer $is_approved
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkVehicleEntryQltyMerge extends ChildModel {

    public $config_code, $is_clr_input;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_qlty_merge';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['config_code', 'union_code', 'is_qty_only', 'is_pending_merge', 'is_approved', 'originating_type', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity', 'created_at', 'updated_at', 'plant_code', 'vehicle_code', 'trip_code', 'chamber_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'tested_by', 'verified_by', 'is_clr_input'], 'safe'],
            [['fat', 'snf', 'chamber_no', 'trip_code'], 'required', 'except' => ['androidsync']],
            [['is_qty_only', 'is_pending_merge', 'is_approved'], 'default', 'value' => 1],
            ['chamber_no', 'unique', 'targetAttribute' => ['chamber_no', 'trip_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMilkVehicleEntryQltyMerge', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_qlty_merge_code' => Yii::t('app', 'Milk Vehicle Entry Qlty Merge Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'chamber_no' => Yii::t('app', 'Compartment No'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'density' => Yii::t('app', 'Density'),
            'protein' => Yii::t('app', 'Protein'),
            'lactose' => Yii::t('app', 'Lactose'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temp' => Yii::t('app', 'Temp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'is_qty_only' => Yii::t('app', 'Is Qty Only'),
            'is_pending_merge' => Yii::t('app', 'Is Pending Merge'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    public function getConfigResult() {
        return TblConfigTxnResult::findOne(['ref_code' => (string) $this->milk_vehicle_entry_qlty_merge_code, 'config_code' => $this->config_code, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty_merge']);
    }

    public function getPlant($trip_code) {
        return TblVehicleTripDetail::find()->select(['source_org_code', 'vehicle_code'])
                        ->where(['is_last_destination' => 1, 'source_org_type' => 'plant', 'trip_code' => $trip_code])->one();
    }

    public function GetClrInput() {
        $isClrInput = Yii::$app->general->getCheckBmcConfiguration($this->union_code, 'is_clr_input', $this->plant_code, 'PLANT', 'PLANT_RECEIPT_CONFIG');
        if ($isClrInput == '') {
            $isClrInput = Yii::$app->general->getUnionConfiguration($this->union_Code, 'is_clr_input', 'PORTAL');
        }
        $this->is_clr_input = $isClrInput;
    }

}
