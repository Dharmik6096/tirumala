<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Dashboard extends Model
{
    public $union_code;
    public $date, $from_date, $to_date, $from_date2, $to_date2, $qlt_param, $shift, $from_date3, $to_date3, $from_shift, $to_shift, $plant_code, $bmc_code, $mcc_code, $dcs_code;
    //public $rememberMe = true;

    //private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['date', 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'union_code' => Yii::t('app', 'Union'),
            'date' => Yii::t('app', 'Date'),
        ];
    }
    
    public function search(){
        
    }

   
}
