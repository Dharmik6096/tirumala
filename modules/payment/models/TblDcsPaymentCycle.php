<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblShift;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;

/**
 * This is the model class for table "tbl_dcs_payment_cycle".
 *
 * @property string $dcs_payment_cycle_code
 * @property string $created_at
 * @property string $created_by
 * @property string $from_date
 * @property string $to_date
 * @property integer $is_active
 * @property string $union_code
 * @property integer $interval_value
 * @property string $updated_at
 * @property string $updated_by
 * @property string $dcs_code
 * @property integer $lock_data
 */
class TblDcsPaymentCycle extends \app\models\ChildModel {

    public $federation_code;
    public $check_month;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_payment_cycle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date','from_shift', 'to_shift', 'interval_value', 'union_code'], 'required'],
            [[ 'created_by', 'union_code', 'updated_by'], 'string'],
            ['interval_value', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Interval Value must be integer.'],
            [['is_billing'], 'validateBilling'],
            [['created_at', 'from_date', 'to_date', 'updated_at', 'dcs_payment_cycle_code', 'lock_billing_process', 'check_month'], 'safe'],
            [['is_active', 'interval_value','lock_billing_process','lock_data'], 'integer'],
            [['to_date'], 'customValidate'],
            //[['from_date', 'to_date'], 'rangeValidate'],
        ];
    }
    
    public function customValidate($attribute, $params) {

        if (!empty($this->from_date) && !empty($this->to_date)) {

            if($this->to_date < $this->from_date){
                $this->addError($attribute, Yii::t('app/validation','To Date Must be Greater Than From Date.'));
                return false;
            }
        }
    }
    public function validateBilling($attribute, $params) {
        if (!in_array($this->$attribute, ['1', '0'])) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be either "0" or "1".'));
            return false;
        }
    }
    public function rangeValidate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            
           $query = $this->find()->where("union_code=" . $this->union_code . " and dcs_payment_cycle_code <> '" . $this->dcs_payment_cycle_code ."' "
                   . "                           and (('" . date('Y-m-d H:i:s', strtotime($this->from_date)) . "' between from_date and to_date) OR ('" . date('Y-m-d H:i:s', strtotime($this->to_date)) . "' between from_date  and to_date))");
            //echo $query->createCommand()->sql;exit;
            $record = $query->one();
//            var_dump($record);exit;
            if ($record) {
                $this->addError($attribute, Yii::t('app/validation', 'Date Range already inserted'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'is_billing' => Yii::t('app', 'Billing'),
            'lock_billing_process' => Yii::t('app', 'Lock Billing Process'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_active' => Yii::t('app', 'Is Active'),           
            'union_code' => Yii::t('app', 'Union'),
            'interval_value' => Yii::t('app', 'Interval Value'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'dcs_code' => Yii::t('app', 'Society Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycleQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPaymentCycleQuery(get_called_class());
    }
    

    public function getNextCycleCode($code,$dcsCode='')
    {
        if(!empty($code) && $code!==0)
        {
            $currCycle=  $this->find()->where(['dcs_payment_cycle_code'=>$code])->one();
            $date= $currCycle->to_date;
            $date=date('Y-m-d', strtotime($date.' +1 day'));
            $nextCycle=  $this->find()->select('dcs_payment_cycle_code')->where(['CAST(from_date AS DATE)'=> $date]);
            if(empty($dcsCode))
            {
                $nextCycle=$nextCycle->one();
            }
            else
            {
                $subQuery=  TblDcsPaymentCycleApplicability::find()->select('dcs_payment_cycle_code')->where(['dcs_code'=>$dcsCode])->all();
                $array=  \yii\helpers\ArrayHelper::getColumn($subQuery, 'dcs_payment_cycle_code');
                $nextCycle=$nextCycle->andWhere(['in','dcs_payment_cycle_code',$array])->one();            
                //var_dump($nextCycle);
            }
        }
        else
            $nextCycle='';
        return empty($nextCycle)?'0':$nextCycle->dcs_payment_cycle_code;
    }
    

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getCode() {

        $val = (new \yii\db\Query)
                ->select(["MAX(dcs_payment_cycle_code) as dcs_payment_cycle_code"])
                ->from('tbl_dcs_payment_cycle')
                //->where(['union_code' => $this->union_code])
                ->one();
       
        $code = (int) $val['dcs_payment_cycle_code'] + 1;

        return $code;
    }

    public function isProcess() {
        if ($this->is_billing || $this->lock_billing_process)
            return !true;
        else
            return !false;
    }

    public function getFromShiftType() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToShiftType() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }
    
    public function disableDelete()
    {
        $app=  TblDcsPaymentCycleApplicability::find()->where(['dcs_payment_cycle_code'=> $this->dcs_payment_cycle_code])->one();
        $next= $this->getNextCycleCode($this->dcs_payment_cycle_code);
        if(!empty($app) || ($next!=0))
            return false;
        else 
            return true;
    }
    
    public function getApplicabilities()
    {
         return $this->hasMany(TblDcsPaymentCycleApplicability::className(), ['dcs_payment_cycle_code' => 'dcs_payment_cycle_code']);
    }
    
    public function unionPaymentCycles($union_code) 
            {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['from_date', 'to_date', 'dcs_payment_cycle_code'])->where(['union_code'=>$union_code])->andWhere(['<','to_date',date('Y-m-d')])->orderBy('from_date ASC')->distinct()->all(), function($model) {
                    return $model['dcs_payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }
    
}
