<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_bmc_collection".
 *
 * @property integer $milk_collection_code
 * @property string $dcs_code
 * @property string $name
 * @property string $mobile_no
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $auto_flag
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $rate_code
 * @property string $error_log
 * @property integer $ack
 * @property string $soc_bmc_flag
 * @property string $dt_date
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp
 * @property string $remarks
 */
class TblBmcCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $union_code, $date, $weigh_time, $testing_time;
    public $dcs_name, $bmc_name, $route_name;

    public static function tableName() {
        return 'tbl_bmc_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
        [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'error_log', 'soc_bmc_flag', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'remarks'], 'string'],
        [['rate_code'], 'string', 'except' => 'saveCreamyData'],
        [['milk_type_code', 'sample_no', 'ack'], 'integer'],
        [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
        [['transporter_code', 'vehicle_code'], 'required', 'when' => function ($model) {
        return $model->collection_type == '2';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbmccollection-collection_type').val() == '2'; 
          }"],
        [['dcs_code'], 'validateDcs'],
        [['dcs_code'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'dcs_code', 'sample_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
        return $this->shift_code;
        }],
        [['collection_type'], 'default', 'value' => 1, 'on' => 'saveCreamyData'],
        [['fat', 'snf', 'rtpl', 'qty', 'dcs_code', 'shift_code', 'milk_type_code', 'date_time_of_collection', 'collection_type', 'milk_quality_type_code'], 'required'],
        [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'transporter_code', 'vehicle_code', 'collection_type', 'date', 'weigh_time', 'testing_time', 'bmc_code'], 'safe'],
        [['density', 'clr', 'lactose', 'protein', 'qlty_auto', 'qty_mode', 'qty_auto', 'no_of_can', 'avg_qlty_param', 'qlty_time', 'qlty_times_no', 'qty_time', 'date_time_of_testing',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_code' => Yii::t('app', 'Milk Collection'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'rate_code' => Yii::t('app', 'Rate'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'dt_date' => Yii::t('app', 'Dt Date'),
            'sms_status' => Yii::t('app', 'Sms Status'),
            'sms_msgid' => Yii::t('app', 'Sms Msgid'),
            'sms_mobile' => Yii::t('app', 'Sms Mobile'),
            'sms_errorlog' => Yii::t('app', 'Sms Errorlog'),
            'sms_timestamp' => Yii::t('app', 'Sms Timestamp'),
            'remarks' => Yii::t('app', 'Remarks'),
            'dcs_name' => Yii::t('app', 'DCS Name'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
        ];
    }

    public function validateDcs($attribute, $params) {
        $dcs_model = new TblDcs();
        $data = $dcs_model->find()->where(['dcs_code' => $this->dcs_code, 'is_active' => 1])->one();
        if (empty($data)) {
            $this->addError($attribute, "Please Enter Valid Society Code");
        }
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getTransporter() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function collectionData($from_date, $to_date, $bmc_code) {
        return $this->find()
                        ->select(['dcs_code', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount', 'shift_code', 'date_time_of_collection', 'sample_no'])
                        ->andFilterWhere(['>=', 'date_time_of_collection', $from_date])
                        ->andFilterWhere(['<=', 'date_time_of_collection', $to_date])
                        ->where(['bmc_code' => $bmc_code])->all();
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code'])->andwhere(['is_active' => 1]);
    }

}
