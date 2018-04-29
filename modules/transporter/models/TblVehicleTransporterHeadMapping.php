<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_transporter_head_mapping".
 *
 * @property integer $vehicle_transporter_head_mapping_code
 * @property integer $transporter_payment_head_code
 * @property string $vehicle_code
 * @property string $wef_date
 * @property string $amount
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblVehicleTransporterHeadMapping extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_transporter_head_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['vehicle_transporter_head_mapping_code'], 'required'],
            [['transporter_payment_head_code', 'is_active'], 'integer'],
            [['is_active'], 'default', 'value' => '1'],
            [['vehicle_code', 'created_by', 'updated_by','remarks'], 'string'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number','min'=>1],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['wef_date','vehicle_code'], function ($attribute, $params) {
                Yii::$app->general->validateVehiclePayment($this);
            }, 'skipOnEmpty' => false],
            [['vehicle_code','transporter_payment_head_code','wef_date','amount'], 'required','on' => 'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_transporter_head_mapping_code' => Yii::t('app', 'Vehicle Transporter Head Mapping Code'),
            'transporter_payment_head_code' => Yii::t('app', 'Transporter Payment Head'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'amount' => Yii::t('app', 'Amount'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    
    
    public function getTransporterPaymentHead(){
        return $this->hasOne(TblTransporterPaymentHead::className(), ['transporter_payment_head_code' => 'transporter_payment_head_code']);
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
