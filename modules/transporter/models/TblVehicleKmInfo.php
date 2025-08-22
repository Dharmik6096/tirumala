<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\payment\models\TblVehiclePayment;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

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
 * @property integer $is_active
 */
class TblVehicleKmInfo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $bmc_code, $route_ref_code, $parsing_no, $plant_code, $mcc_plant_code;

    public static function tableName() {
        return 'tbl_vehicle_km_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['km_info_code'], 'required'],
                [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['shift_code'], 'integer', 'message' => Yii::t('app/validation', 'Please enter valid Shift Code.'), 'on' => ['importCsv']],
                [['wef_date', 'morning_kms', 'evening_kms', 'wef_date', 'shift_code', 'morning_arrival_time', 'evening_arrival_time', 'morning_grace_time', 'evening_grace_time'], 'required'],
                [['transporter_code'], 'required', 'except' => ['importCsv']],
                [['union_code', 'total_kms'], 'required', 'except' => ['importCsv']],
                [['route_ref_code', 'parsing_no'], 'required', 'on' => ['importCsv']],
                [['data_lock'], 'default', 'value' => 0],
                [['is_active'], 'default', 'value' => 1],
                [['vehicle_code', 'route_code', 'transporter_code', 'created_by', 'updated_by'], 'safe'],
                [['wef_date', 'created_at', 'updated_at', 'shift_code', 'data_lock', 'plant_code', 'mcc_plant_code', 'route_ref_code'], 'safe'],
                [['morning_kms', 'evening_kms', 'extra_kms', 'total_kms', 'morning_grace_time', 'evening_grace_time'], 'number', 'min' => 0],
                [['morning_arrival_time', 'evening_arrival_time'], 'date', 'format' => 'php:H:i'],
                [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['wef_date'], 'convertDate', 'on' => ['importCsv']],
//            [['wef_date'], 'wefValidate', 'on' => 'create'],
//            [['morning_kms'], 'routeValidate'],
//            [['wef_date'], 'wefPaidValidate','on'=>'update'],
            [['wef_date', 'vehicle_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->validateVehiclePayment($this);
                    }
                }, 'skipOnEmpty' => false],
                [['vehicle_code'], 'importFieldSet', 'on' => ['importCsv']],
                [['transporter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblTransporter::className(), 'targetAttribute' => ['transporter_code' => 'transporter_code'], 'on' => ['importCsv']],
                [['vehicle_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['vehicle_code' => 'vehicle_code'], 'on' => ['importCsv']],
                [['route_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_code' => 'route_code'], 'on' => ['importCsv']],
                [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
                [['route_ref_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_ref_code' => 'ref_code'], 'on' => 'importCsv'],
                [['parsing_no'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['parsing_no' => 'parsing_no'], 'on' => 'importCsv'],
                [['parsing_no'], 'importFieldSet', 'skipOnError' => true, 'on' => ['importCsv']],
                [['route_code', 'vehicle_code'], 'required'],
                [['route_code'], 'unique', 'targetAttribute' => ['route_code', 'wef_date', 'shift_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
                [['morning_arrival_time', 'morning_grace_time', 'evening_arrival_time', 'evening_grace_time'], 'safe'],
                [['morning_arrival_time', 'evening_arrival_time'], 'date', 'format' => 'php:H:i'],
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
            'is_active' => Yii::t('app', 'Is Active'),
            'shift_code' => Yii::t('app', 'Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'morning_arrival_time' => Yii::t('app', 'Arrival Time(M)(24:HR HH:MM)'),
            'evening_arrival_time' => Yii::t('app', 'Arrival Time(E)(24:HR HH:MM)'),
            'morning_grace_time' => Yii::t('app', 'Grace Time(M)(Min)'),
            'evening_grace_time' => Yii::t('app', 'Grace Time(E)(Min)'),
            'parsing_no' => Yii::t('app', 'Parsing No.'),
            'route_ref_code' => Yii::t('app', 'Route Ref Code'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'Mcc'),
            'bmc_code' => Yii::t('app', 'Bmc'),
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
        if (empty($this->getErrors())) {
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getRouteRefCode() {
        return $this->hasOne(TblRouteMapping::className(), ['ref_code' => 'route_ref_code']);
    }

    public function getParsingNo() {
        return $this->hasOne(TblVehicleMaster::className(), ['parsing_no' => 'parsing_no']);
    }

    public function importFieldSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->route_code = Yii::$app->general->getforeignkey($this->routeRefCode, 'route_code');
            $this->vehicle_code = Yii::$app->general->getforeignkey($this->parsingNo, 'vehicle_code');
            $this->transporter_code = Yii::$app->general->getforeignkey($this->vehicle, 'transporter_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->transporterCode, 'union_code');
            $this->total_kms = $this->morning_kms + $this->evening_kms;
        }
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
            $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
    }

    public function getdateWiseVehicleRouteList($vehicle, $date) {
        $data = $this->find()
                        ->select(['route_code'])
                        ->where(['vehicle_code' => $vehicle])
                        ->andFilterWhere(['<=', 'wef_date', date('Y-m-d', strtotime($date))])
                        ->groupBy('route_code')->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'route_code', function($data) {
                    return Yii::$app->general->getforeignkey($data->routeCode, 'route_name');
                });
        return $array;
    }

    public function getRouteVehicleDetail($route_code, $datetime) {
        return $this->find()
                        ->select(['vehicle_code', 'morning_arrival_time', 'morning_grace_time', 'evening_arrival_time', 'evening_grace_time'])
                        ->where(['route_code' => $route_code])
                        ->andFilterWhere(['<=', 'wef_date', date('Y-m-d H:i:s', strtotime($datetime))])
                        ->orderBy('wef_date DESC')
                        ->one();
    }

    public function getRouteTransporterList($route_codes) {
        $data = $this->find()
                        ->select(['t.transporter_code', 'tbl_transporter.transporter_name', 'tbl_transporter.vendor_code'])
                        ->distinct()
                        ->alias('t')
                        ->joinWith(['transporterCode'])
                        ->where(['t.route_code' => $route_codes, 'tbl_transporter.is_active' => 1])
                        ->asArray()->all();
        $array = ArrayHelper::map($data, 'transporter_code', function($data) {
                    return $data['transporter_name'] . '(' . $data['vendor_code'] . ')';
                });
        return $array;
    }

    public function getLatestVehicleData($bmcCode) {
        return $this->find()
                        ->alias('v')
                        ->select(['v.vehicle_code'])
                        ->innerJoin('tbl_route_mapping r', 'r.route_code = v.route_code')
                        ->where(['r.to_type' => 'bmc', 'r.to_dest' => $bmcCode, 'v.vehicle_code' => $this->vehicle_code, 'r.is_active' => 1])
                        ->andWhere(['<=', 'v.created_at', date('Y-m-d H:i:s')])
                        ->orderBy(['v.created_at' => SORT_DESC])
                        ->one();
    }

}
