<?php

namespace app\modules\transporter\models;

use Yii;
/**
 * This is the model class for table "tbl_vehicle_billing_type".
 *
 * @property integer $vehicle_billing_code
 * @property integer $vehicle_code
 * @property integer $billing_type_code
 * @property string $wef_date
 * @property string $remarks
 */
class TblVehicleBillingType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_billing_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_code', 'billing_type_code'], 'integer'],
            [['wef_date', 'created_at', 'updated_at', 'delete_at','is_active'], 'safe'],
            [['remarks', 'created_by', 'updated_by', 'delete_by'], 'string'],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['wef_date','vehicle_code'], function ($attribute, $params) {
                Yii::$app->general->validateVehiclePayment($this);
            }, 'skipOnEmpty' => false],
            [['vehicle_code','billing_type_code','wef_date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_billing_code' => Yii::t('app', 'Vehicle Billing Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'billing_type_code' => Yii::t('app', 'Billing Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'delete_at' => Yii::t('app', 'Delete At'),
            'delete_by' => Yii::t('app', 'Delete By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
    
    
    public function getBillingTypeCode()
    {
        return $this->hasOne(TblBillingType::className(), ['billing_type_code' => 'billing_type_code']);
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
    
    public function getBillingType(){
        return $this->hasOne(TblBillingType::className(), ['billing_type_code' => 'billing_type_code']);
    }
}
