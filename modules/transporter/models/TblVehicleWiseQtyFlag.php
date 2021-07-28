<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_wise_qty_flag".
 *
 * @property string $vehicle_wise_qty_flag_code
 * @property string $vehicle_code
 * @property string $wef_date
 * @property string $qty_flag
 */
class TblVehicleWiseQtyFlag extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_wise_qty_flag';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_wise_qty_flag_code'], 'required'],
                [['vehicle_wise_qty_flag_code', 'vehicle_code', 'qty_flag'], 'safe'],
                [['wef_date', 'created_at', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vehicle_wise_qty_flag_code' => Yii::t('app', 'Vehicle Wise Qty Flag Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'qty_flag' => Yii::t('app', 'Qty Flag'),
        ];
    }

    public function getExistingData($model) {
        return $this->find()
                        ->where(['vehicle_code' => $model->vehicle_code])
                        ->orderBy('vehicle_wise_qty_flag_code Desc')
                        ->one();
    }

    public function getExistingFlag() {
        return $this->find()
                        ->where(['vehicle_code' => $this->vehicle_code, 'wef_date' => $this->wef_date, 'qty_flag' => $this->qty_flag])
                        ->one();
    }

}
