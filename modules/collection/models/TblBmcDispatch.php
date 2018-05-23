<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_dispatch".
 *
 * @property integer $bmc_dispatch_code
 * @property string $fat
 * @property string $snf
 * @property string $mbrt
 * @property string $temprature
 * @property integer $milk_test
 * @property integer $alcohole_test
 * @property string $vehicle_code
 * @property string $vehicle_in_time
 * @property string $vehicle_out_time
 * @property string $destination_code
 * @property string $destination_type
 * @property string $actual_qty
 * @property string $dispatch_qty
 * @property string $dispatch_datetime
 * @property integer $dispatch_shift
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBmcDispatch extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fat', 'snf', 'mbrt', 'temprature', 'actual_qty', 'dispatch_qty'], 'number'],
            [['milk_test', 'alcohole_test', 'dispatch_shift', 'milk_type_code', 'milk_quality_type_code'], 'integer'],
            [['vehicle_code', 'destination_code', 'destination_type', 'bmc_code', 'route_code', 'created_by', 'updated_by'], 'string'],
            [['vehicle_in_time', 'vehicle_out_time', 'dispatch_datetime', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_code' => Yii::t('app', 'Bmc Dispatch Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temprature' => Yii::t('app', 'Temprature'),
            'milk_test' => Yii::t('app', 'Milk Test'),
            'alcohole_test' => Yii::t('app', 'Alcohole Test'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'vehicle_in_time' => Yii::t('app', 'Vehicle In Time'),
            'vehicle_out_time' => Yii::t('app', 'Vehicle Out Time'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'actual_qty' => Yii::t('app', 'Actual Qty'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'dispatch_datetime' => Yii::t('app', 'Dispatch Datetime'),
            'dispatch_shift' => Yii::t('app', 'Dispatch Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getDispatchList($from_date, $to_date) {
        return $this->find()
                        ->where(['bmc_code' => $this->bmc_code])
                        ->andFilterWhere(['>=', 'dispatch_datetime', $from_date])
                        ->andFilterWhere(['<=', 'dispatch_datetime', $to_date])
                        ->all();
    }

}
