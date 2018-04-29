<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_mobile_oil_rate_master".
 *
 * @property integer $mobile_oil_rate_master_code
 * @property string $rate
 * @property string $wef_date
 * @property integer $vehicle_code
 * @property string $km_info
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMobileOilRateMaster extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mobile_oil_rate_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate'], 'number'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['vehicle_code','wef_date','rate'], 'required'],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['wef_date','vehicle_code'], function ($attribute, $params) {
                Yii::$app->general->validateVehiclePayment($this);
            }, 'skipOnEmpty' => false],
            [['km_info', 'created_by', 'updated_by'], 'string'],
            [['rate','km_info'], 'number', 'min'=>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobile_oil_rate_master_code' => Yii::t('app', 'Mobile Oil Rate Master Code'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'km_info' => Yii::t('app', 'Km Info'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    
    
    public function getVehicle(){
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }
    
    public function wefValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['=','vehicle_code',$this->vehicle_code])
                ->andWhere(['>=','wef_date',$wef_date])
                ->orderBy('wef_date desc')
                ->one();
        if(!empty($data)){
            $this->addError($attribute, "Please select Wef Date greater than '".Yii::$app->controls->view_date($data->wef_date)."'");
        }
    }
}
