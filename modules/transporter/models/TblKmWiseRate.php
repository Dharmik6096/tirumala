<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_km_wise_rate".
 *
 * @property integer $km_code
 * @property string $rate
 * @property string $from_km
 * @property string $to_km
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblKmWiseRate extends \app\models\ChildModel {

    public $parsing_no;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_km_wise_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_code', 'wef_date', 'from_km', 'to_km', 'rate', 'union_code', 'transporter_code'], 'required', 'except' => 'importCsv'],
                [['rate', 'from_km', 'to_km'], 'number', 'min' => 0],
                [['wef_date', 'created_at', 'updated_at', 'union_code', 'transporter_code', 'vehicle_code', 'parsing_no'], 'safe'],
                [['created_by', 'updated_by'], 'string'],
                [['to_km'], 'kmValidate'],
            //  [['wef_date'], 'wefValidate', 'on' => 'create'],
//            [['wef_date', 'vehicle_code'], function ($attribute, $params) {
//            Yii::$app->general->validateVehiclePayment($this);
//        }, 'skipOnEmpty' => false],
            [['from_km'], 'rangeValidate', 'skipOnEmpty' => TRUE],
            //[['from_km', 'to_km', 'rate'], 'number', 'min' => 1],
            //[['to_km'], 'kmValidate'],
            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
                [['wef_date'], 'setFieldImport', 'on' => 'importCsv'],
                [['parsing_no'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['parsing_no' => 'parsing_no'], 'on' => 'importCsv'],
                [['parsing_no', 'wef_date', 'from_km', 'to_km', 'rate', 'transporter_code'], 'required', 'on' => 'importCsv'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'km_code' => Yii::t('app', 'Km Code'),
            'rate' => Yii::t('app', 'Rate'),
            'from_km' => Yii::t('app', 'From Km'),
            'to_km' => Yii::t('app', 'To Km'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function rangeValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $query = $this->find()->where('wef_date=\'' . $wef_date . '\' and  ((' . $this->from_km . '  between from_km and to_km) OR (' . $this->to_km . ' between from_km and to_km))')
                ->andWhere(['vehicle_code' => $this->vehicle_code])
                ->andFilterWhere(['<>', 'km_code', $this->km_code]);
        $record = $query->one();
        if (!empty($record)) {
            $this->addError($attribute, Yii::t('app/validation', 'Can not use range in between of used range for same WEF Date.'));
            return false;
        }
    }

//    public function wefValidate($attribute, $params) {
//        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
//        $data = $this->find()
//                ->where(['=', 'vehicle_code', $this->vehicle_code])
//                ->andWhere(['>=', 'wef_date', $wef_date])
//                ->orderBy('wef_date desc')
//                ->one();
//        if (!empty($data)) {
//            $this->addError($attribute, "Please select Wef Date greater than '" . Yii::$app->controls->view_date($data->wef_date) . "'");
//        }
//    }
//
//    public function kmRangeValidate($attribute, $params) {
//        $data = $this->find()
//                ->where(['=', 'vehicle_code', $this->vehicle_code])
//                ->andFilterWhere(['>=', 'to_km', $this->from_km])
//                ->andFilterWhere(['<>', 'km_code', $this->km_code])
//                ->orderBy('wef_date desc')
//                ->one();
//        if (!empty($data)) {
//            $this->addError($attribute, "Please select From Km greater than '" . $data->to_km . "'");
//        }
//    }

    public function kmValidate($attribute, $params) {
        if ($this->from_km > $this->to_km) {
            $this->addError($attribute, "To km is not less than From Km");
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getParsingNo() {
        return $this->hasOne(TblVehicleMaster::className(), ['parsing_no' => 'parsing_no']);
    }

    public function setFieldImport($attribute, $params) {
        $parsingNo = $this->parsingNo;
        if (!empty($parsingNo)) {
            $this->vehicle_code = $parsingNo->vehicle_code;
            $this->transporter_code = $parsingNo->transporter_code;
            $this->union_code = $parsingNo->union_code;
        }
    }

}
