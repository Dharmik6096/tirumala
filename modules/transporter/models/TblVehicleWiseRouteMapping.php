<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblRoutes;
/**
 * This is the model class for table "tbl_vehicle_wise_route_mapping".
 *
 * @property integer $vehicle_wise_route_code
 * @property integer $vehicle_code
 * @property integer $route_code
 * @property string $wef_date
 * @property integer $is_active
 */
class TblVehicleWiseRouteMapping extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_wise_route_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_code', 'route_code', 'is_active'], 'integer'],
            [['wef_date'], 'safe'],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['vehicle_code','route_code','wef_date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_wise_route_code' => Yii::t('app', 'Vehicle Wise Route Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'route_code' => Yii::t('app', 'Route'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
    
    
    public function getVehicle(){
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }  
    
    public function getRouteCode()
    {
        return $this->hasOne(TblRoutes::className(), ['route_code' => 'route_code']);
    }
    
    public function wefValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['=','vehicle_code',$this->vehicle_code])
                ->andWhere(['>=','wef_date',$wef_date])
                ->andWhere(['=','route_code',$this->route_code])
                ->orderBy('wef_date desc')
                ->one();
        if(!empty($data)){
            $this->addError($attribute, "Please select Wef Date greater than '".Yii::$app->controls->view_date($data->wef_date)."'");
        }
    }
}
