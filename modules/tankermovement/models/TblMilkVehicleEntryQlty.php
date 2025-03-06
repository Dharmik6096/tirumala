<?php

namespace app\modules\tankermovement\models;

use app\models\ChildModel;
use app\modules\globalmaster\models\TblVehicleType;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblVehicleMaster;
use Yii;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_qlty".
 *
 * @property integer $milk_vehicle_entry_qlty_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $vehicle_code
 * @property string $arrival_datetime
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
 * @property string $status
 * @property string $status_datetime
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
class TblMilkVehicleEntryQlty extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_qlty';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['arrival_datetime', 'status_datetime', 'created_at', 'updated_at', 'lot_datetime', 'lot_no'], 'safe'],
            [['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['fat', 'snf', 'chamber_no'], 'required'],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code'], 'string', 'max' => 6],
            [['vehicle_code', 'trip_code'], 'string', 'max' => 20],
            [['chamber_no', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_qlty_code' => Yii::t('app', 'Milk Vehicle Entry Qlty Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'arrival_datetime' => Yii::t('app', 'Arrival Datetime'),
            'trip_code' => Yii::t('app', 'Trip '),
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
            'status' => Yii::t('app', 'Status'),
            'status_datetime' => Yii::t('app', 'Status Datetime'),
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
            'lot_datetime' => Yii::t('app', 'Lot Datetime'),
            'lot_no' => Yii::t('app', 'Lot No'),
        ];
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMilkVehicleEntryQlty() {
        $records = $this->find()
                ->where(['trip_code' => $this->trip_code, 'union_code' => $this->union_code])
                ->andWhere(['!=', 'status', 'discarded'])
                ->all();
        if (!empty($records)) {
            $plantLotCreationInterval = Yii::$app->general->getUnionConfiguration($this->union_code, 'plant_lot_creation_interval  ', 'PORTAL');
            $totalRecords = count($records);
            $doneRecordsCount = 0;
            $maxStatusDatetime = NULL;
            foreach ($records as $record) {
                if ($record->status === 'done') {
                    $doneRecordsCount++;
                }
                $currentStatusDatetime = strtotime($record->status_datetime);
                if ($maxStatusDatetime === NULL || $currentStatusDatetime > $maxStatusDatetime) {
                    $maxStatusDatetime = $currentStatusDatetime;
                }
            }
            $currentTime = time();
            $intervalInSeconds = $plantLotCreationInterval * 3600;
            if (($currentTime - $maxStatusDatetime) > $intervalInSeconds || $totalRecords !== $doneRecordsCount) {
                return ['success' => 0, 'record_data' => []];
            }

            $formattedRecords = [];
            foreach ($records as $record) {
                $formattedRecords[$record->chamber_no] = [
                    'fat' => number_format($record->fat, 2, '.', ''),
                    'snf' => number_format($record->snf, 2, '.', ''),
                    'clr' => number_format($record->clr, 2, '.', ''),
                    'water' => number_format($record->water, 2, '.', ''),
                    'density' => number_format($record->density, 2, '.', ''),
                    'protein' => number_format($record->protein, 2, '.', ''),
                    'lactose' => number_format($record->lactose, 2, '.', ''),
                    'freezing_point' => number_format($record->freezing_point, 2, '.', ''),
                    'mbrt' => number_format($record->mbrt, 2, '.', ''),
                    'temp' => number_format($record->temp, 2, '.', ''),
                    'acidity' => number_format($record->acidity, 2, '.', ''),
                ];
            }
            return ['success' => 1, 'record_data' => $formattedRecords];
        }
        return ['success' => 0, 'record_data' => []];
    }

}
