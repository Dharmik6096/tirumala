<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Dashboard extends Model {

    public $union_code, $widget_type, $rmrd_widgets, $farmer_widgets, $from_date4, $from_date5, $to_date4, $to_date5, $previous_hit, $current_hit, $from_date6, $to_date6, $widget_for, $plant_widgets;
    public $date, $from_date, $to_date, $from_date2, $to_date2, $qlt_param, $shift, $from_date3, $to_date3, $from_shift, $to_shift, $plant_code, $bmc_code, $mcc_code, $dcs_code, $hidden_from_date, $hidden_to_date, $dpu_status, $dup_search_date, $dpu_shift, $from_date_milk_analysis, $to_date_milk_analysis;
    public $customer_type, $member_code;
    public $mav_from_shift, $mav_to_shift, $mag_from_shift, $mag_to_shift, $ccs_from_shift, $ccs_to_shift, $performance_type;
    public $state_code, $region_code, $area_code, $month_year, $area_bmc_code, $union;

    //public $rememberMe = true;
    //private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            //[['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            [['date', 'from_date_milk_analysis', 'to_date_milk_analysis', 'customer_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'union_code' => Yii::t('app', 'Union'),
            'date' => Yii::t('app', 'Date'),
            'previous_hit' => Yii::t('app', 'No. of Hits'),
            'current_hit' => Yii::t('app', 'No. of Hits'),
            'dpu_status' => Yii::t('app', 'DPU Status'),
        ];
    }

    public function search() {
        
    }

    public function getUnionCode($field) {
        $model = new TblUnions;
        $data = $model->find()
                        ->where(['union_code' => $this->union_code])->one();
        if (!empty($data)) {
            return $data->$field;
        } else {
            return !empty($this->union_code) ? '' : Yii::t('app', 'All');
        }
    }

    public function getMccPlantCode($field) {
        $model = new TblMccPlant;
        $data = $model->find()
                        ->where(['mcc_plant_code' => $this->mcc_code])->one();
        if (!empty($data)) {
            return $data->$field;
        } else {
            return !empty($this->mcc_code) ? '' : Yii::t('app', 'All');
            return '';
        }
    }

}
