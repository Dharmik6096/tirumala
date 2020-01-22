<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_dpu_incentive_master".
 *
 * @property integer $incentive_master_code
 * @property string $dcs_code
 * @property string $m_cutoff_time
 * @property string $e_cutoff_time
 * @property string $m_start_time
 * @property string $e_start_time
 * @property string $m_lock_time
 * @property string $e_lock_time
 * @property string $inc_rate
 * @property string $inc_deduction
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDpuIncentiveMaster extends \app\models\ChildModel {

    public $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dpu_incentive_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['m_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/'],
            [['dcs_code', 'm_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'inc_rate', 'inc_deduction', 'union_code', 'from_date', 'to_date'], 'required'],
            [['dcs_code', 'm_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['inc_rate', 'inc_deduction'], 'number'],
            [['created_at', 'updated_at', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['dcs_code'], 'unique'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['e_start_time', 'm_cutoff_time', 'e_cutoff_time', 'm_lock_time', 'e_lock_time'], 'timeValidate', 'except' => ['update']],
            [['dcs_code'], 'timeValidate', 'on' => ['update']],
            [['inc_rate', 'inc_deduction'], 'number', 'min' => 0],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'incentive_master_code' => Yii::t('app', 'Incentive Master Code'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'm_cutoff_time' => Yii::t('app', 'Cutoff Time(M)'),
            'e_cutoff_time' => Yii::t('app', 'Cutoff Time(E)'),
            'm_start_time' => Yii::t('app', 'Start Time(M)'),
            'e_start_time' => Yii::t('app', 'Start Time(E)'),
            'm_lock_time' => Yii::t('app', 'Lock Time(M)'),
            'e_lock_time' => Yii::t('app', 'Lock Time(E)'),
            'inc_rate' => Yii::t('app', 'Inc. Rate'),
            'inc_deduction' => Yii::t('app', 'Inc. Deduction'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function timeValidate($attribute, $params) {
        $hasError = false;
        $array = array(
            array('m_start_time', 'e_start_time'),
            array('m_start_time', 'm_cutoff_time'),
            array('m_cutoff_time', 'e_cutoff_time'),
            array('e_start_time', 'e_cutoff_time'),
            array('m_start_time', 'm_lock_time'),
            array('m_cutoff_time', 'm_lock_time'),
            array('m_lock_time', 'e_lock_time'),
            array('e_start_time', 'e_lock_time'),
            array('e_cutoff_time', 'e_lock_time'),
            array('m_lock_time', 'e_start_time'),
        );
        foreach ($array as $a) {
            $this->compareTime($a[0], $a[1], $hasError);
        }
        return $hasError;
    }

    private function compareTime($lessTime, $greateTime, &$hasError) {
        if (!empty($this->{$lessTime}) && !empty($this->{$greateTime}) && ($this->{$greateTime} <= $this->{$lessTime})) {
            $this->addError($greateTime, Yii::t('app/validation', $this->getAttributeLabel($greateTime) . ' Must be Greater than ' . $this->getAttributeLabel($lessTime)));
            $hasError = true;
        }
    }

}
