<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\payment\models\TblVehiclePayment;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_vehicle_km_info".
 *
 * @property string $km_info_code
 * @property string $vehicle_code
 * @property string $route_code
 * @property string $transporter_code
 * @property string $wef_date
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $extra_kms
 * @property string $total_kms
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $delete_at
 * @property string $delete_by
 * @property integer $is_active
 */
class TblVehicleKmInfo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $bmc_code,$union_code;

    public static function tableName() {
        return 'tbl_vehicle_km_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['km_info_code'], 'required'],
            [['data_lock'], 'default', 'value' => 0],
            [['vehicle_code', 'route_code', 'transporter_code', 'created_by', 'updated_by', 'delete_by'], 'string'],
            [['wef_date', 'created_at', 'updated_at', 'delete_at', 'shift_code', 'data_lock'], 'safe'],
            [['morning_kms', 'evening_kms', 'extra_kms', 'total_kms'], 'number', 'min' => 1],
            [['wef_date'], 'wefValidate', 'on' => 'create'],
            [['morning_kms'], 'routeValidate'],
//            [['wef_date'], 'wefPaidValidate','on'=>'update'],
            [['wef_date', 'vehicle_code'], function ($attribute, $params) {
            Yii::$app->general->validateVehiclePayment($this);
        }, 'skipOnEmpty' => false],
            [['route_code', 'vehicle_code', 'transporter_code', 'wef_date', 'morning_kms', 'evening_kms', 'total_kms'], 'required', 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'km_info_code' => Yii::t('app', 'Km Info Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'route_code' => Yii::t('app', 'Route'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'morning_kms' => Yii::t('app', 'Morning Km'),
            'evening_kms' => Yii::t('app', 'Evening Km'),
            'extra_kms' => Yii::t('app', 'Extra Km'),
            'total_kms' => Yii::t('app', 'Total Km'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'delete_at' => Yii::t('app', 'Delete At'),
            'delete_by' => Yii::t('app', 'Delete By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'shift_code' => Yii::t('app', 'Shift'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVehicleKmInfoQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVehicleKmInfoQuery(get_called_class());
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function existData($model) {
        $data = $this->find()
                ->where(['=', 'vehicle_code', $model->vehicle_code])
                ->andWhere(['<', 'wef_date', $model->wef_date])
                ->andWhere(['=', 'route_code', $model->route_code])
                ->andWhere(['=', 'transporter_code', $model->transporter_code])
                ->orderBy('wef_date desc')
                ->one();

        $payment_model = new TblVehiclePayment();
        $payment_data = $payment_model->find()
                ->where(['=', 'vehicle_code', $model->vehicle_code])
                ->andWhere(['!=', 'status', 'processed'])
                ->orderBy('to_date desc')
                ->one();
        $exist = [];
        if (!empty($data)) {
            $data_date = $data->wef_date;
            if (!empty($payment_data) && ($payment_data->to_date > $data_date)) {
                $data_date = $payment_data->to_date;
            }
            $exist_wef_date = Yii::$app->formatter->asDate($data_date, DATE_FORMAT);
            $diff = date_diff(date_create($exist_wef_date), date_create($model->wef_date));
            $num = $diff->format("%R%a days");
            if ($num > 1) {
                for ($i = 1; $i <= $num - 1; $i++) {
                    $exist_wef_date = date('Y-m-d', strtotime('1' . ' day', strtotime($exist_wef_date)));
                    $model = new TblVehicleKmInfo();
                    $model->attributes = $data->attributes;
                    $model->wef_date = $exist_wef_date;
                    array_push($exist, $model);
                }
            }
        }
        return $exist;
    }

    public function wefValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['=', 'vehicle_code', $this->vehicle_code])
                ->andWhere(['>=', 'wef_date', $wef_date])
                ->andWhere(['=', 'route_code', $this->route_code])
                ->andWhere(['=', 'transporter_code', $this->transporter_code])
                ->andWhere(['=', 'shift_code', $this->shift_code])
                ->orderBy('wef_date desc')
                ->one();
        if (!empty($data)) {
            $this->addError($attribute, "Please select Wef Date greater than '" . Yii::$app->controls->view_date($data->wef_date) . "'");
        }
    }

    public function routeValidate($attribute, $params) {
        if (($this->morning_kms != '' || $this->evening_kms != '' || $this->extra_kms != '' || $this->total_kms != '') && $this->route_code == '') {
            $this->addError('route_code', "Route can not be Blank.");
        }
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getVehicleList($from_date, $to_date) {
        return $this->find()->where(['transporter_code' => $this->transporter_code, 'data_lock' => 0])
                        ->andWhere(['or', ['>=', 'wef_date', $from_date], ['<=', 'wef_date', $to_date]])
                        ->all();
    }

}
