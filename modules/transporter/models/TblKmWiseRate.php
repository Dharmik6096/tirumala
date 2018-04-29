<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
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
class TblKmWiseRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_km_wise_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate', 'from_km', 'to_km'], 'number'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'string'],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['wef_date','vehicle_code'], function ($attribute, $params) {
                Yii::$app->general->validateVehiclePayment($this);
            }, 'skipOnEmpty' => false],
            [['from_km'], 'kmRangeValidate'],
            [['from_km','to_km','rate'], 'number', 'min'=>1],
            [['to_km'], 'kmValidate'],
            [['vehicle_code','wef_date','from_km','to_km','rate'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
    
    public function kmRangeValidate($attribute, $params){
        $data = $this->find()
                ->where(['=','vehicle_code',$this->vehicle_code])
                ->andFilterWhere(['>=','to_km',$this->from_km])
                ->andFilterWhere(['<>','km_code',$this->km_code])
                ->orderBy('wef_date desc')
                ->one();
        if(!empty($data)){
            $this->addError($attribute, "Please select From Km greater than '".$data->to_km."'");
        }
    }
    
    public function kmValidate($attribute, $params){
        if($this->from_km > $this->to_km){
            $this->addError($attribute, "To km is not less than From Km");
        }
    }
}
