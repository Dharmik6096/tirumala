<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_fuel_rate_master".
 *
 * @property integer $fuel_rate_code
 * @property string $rate
 * @property string $wef_date
 * @property string $union_code
 * @property integer $fuel_type_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblFuelRateMaster extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fuel_rate_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate'], 'number'],
            [['union_code','wef_date','fuel_type_code','rate'], 'required'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['wef_date'], 'wefValidate','on'=>'create'],
            [['fuel_type_code'], 'integer'],
            [['rate'], 'number', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fuel_rate_code' => Yii::t('app', 'Fuel Rate Code'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'union_code' => Yii::t('app', 'Union'),
            'fuel_type_code' => Yii::t('app', 'Fuel Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    public function getFuelType(){
        return $this->hasOne(TblFuelTypeMaster::className(), ['fuel_type_code' => 'fuel_type_code']);
    }
    
    public function wefValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $data = $this->find()
                ->where(['=','union_code',$this->union_code])
                ->andWhere(['>=','wef_date',$wef_date])
                ->andWhere(['=','fuel_type_code',$this->fuel_type_code])
                ->orderBy('wef_date desc')
                ->one();
        if(!empty($data)){
            $this->addError($attribute, "Please select Wef Date greater than '".Yii::$app->controls->view_date($data->wef_date)."'");
        }
    }
}
