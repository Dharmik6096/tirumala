<?php

namespace app\modules\general\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_collection_incentive_deduction".
 *
 * @property integer $incentive_deduction_id
 * @property string $dcs_code
 * @property string $from_time
 * @property string $to_time
 * @property integer $scheme_type
 * @property integer $shift_code
 * @property string $amount
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblCollectionIncentiveDeduction extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_incentive_deduction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['from_time', 'to_time', 'from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['scheme_type', 'shift_code'], 'integer'],
            [['amount'], 'number'],
            [['dcs_code', 'from_time', 'to_time', 'from_date', 'to_date', 'shift_code', 'amount', 'scheme_type'], 'required', 'on' => ['create']],
            [['amount'], 'number', 'min' => 0],
            [['from_time', 'to_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/', 'on' => ['create']],
            [['from_time'], 'timeValidate', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'incentive_deduction_id' => Yii::t('app', 'Incentive Deduction ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_time' => Yii::t('app', 'From Time'),
            'to_time' => Yii::t('app', 'To Time'),
            'scheme_type' => Yii::t('app', 'Scheme Type'),
            'shift_code' => Yii::t('app', 'Shift'),
            'amount' => Yii::t('app', 'Amount'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDpuIncentive() {
        return $this->hasOne(TblDpuIncentiveMaster::className(), ['dcs_code' => 'dcs_code']);
    }

    public function timeValidate($attribute, $params) {
        $morning_start = Yii::$app->general->getforeignkey($this->dpuIncentive, 'm_start_time');
        $morning_lock = Yii::$app->general->getforeignkey($this->dpuIncentive, 'm_lock_time');
        $eve_start = Yii::$app->general->getforeignkey($this->dpuIncentive, 'e_start_time');
        $eve_lock = Yii::$app->general->getforeignkey($this->dpuIncentive, 'e_lock_time');

        if (!empty($this->from_time) && !empty($this->to_time) && ($this->to_time <= $this->from_time)) {
            $this->addError('to_time', Yii::t('app/validation', $this->getAttributeLabel('to_time') . ' Must be Greater than ' . $this->getAttributeLabel('from_time')));
        } else if ($this->shift_code == 1) {
            if (!empty($morning_start) && !empty($this->from_time) && (($this->from_time) < $morning_start || ($this->from_time) > $morning_lock ) || (($this->to_time) < $morning_start || ($this->to_time) > $morning_lock)) {
                $this->addError('from_time', Yii::t('app/validation', 'From Time and To Time Range between ' . $morning_start . ' and ' . $morning_lock));
            }
        } elseif ($this->shift_code == 2) {
            if (!empty($eve_start) && !empty($this->from_time) && (($this->from_time) < $eve_start || ($this->from_time) > $eve_lock ) || (($this->to_time) < $eve_start || ($this->to_time) > $eve_lock)) {
                $this->addError('from_time', Yii::t('app/validation', 'From Time and To Time Range between ' . $eve_start . ' and ' . $eve_lock));
            }
        }

        if (!empty($this->from_date) && !empty($this->to_date) && date('Y-m-d', strtotime($this->to_date)) < date('Y-m-d', strtotime($this->from_date))) {
            $this->addError('to_date', Yii::t('app/validation', $this->getAttributeLabel('to_date') . ' Must be Greater than ' . $this->getAttributeLabel('from_date')));
        }
        $dateData = $this->find()
                ->where('dcs_code=\'' . $this->dcs_code . '\' and shift_code=\'' . $this->shift_code . '\'  and  ((\'' . $this->from_date . '\'  between from_date and to_date) OR (\'' . $this->to_date . '\' between from_date  and to_date))')
//                ->where(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code])
//                ->andWhere(['or', ['between', $this->from_date, 'from_date', 'to_date'], ['between', $this->to_date, 'from_date', 'to_date']])
                ->andWhere(['or', ['between', 'CAST(from_date AS DATE)', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))], ['between', 'CAST(to_date AS DATE)', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))]])
                ->andWhere(['!=', 'from_date', $this->from_date])
                ->andWhere(['!=', 'to_date', $this->to_date])
                ->andfilterWhere(['!=', 'incentive_deduction_id', $this->incentive_deduction_id])
                ->all();

        if (empty($dateData)) {
            $from_time = $this->from_time . ':00';
            $to_time = $this->to_time . ':00';
//            $query = $this->find()
//                    ->where('incentive_deduction_id !=\'' . $this->incentive_deduction_id . '\' and dcs_code=\'' . $this->dcs_code . '\' and shift_code=\'' . $this->shift_code . '\' and from_date=\'' . $this->from_date . '\' and to_date=\'' . $this->to_date . '\'  and ((\'' . $from_time . '\'  between from_time and to_time) OR (\'' . $to_time . '\' between from_time  and to_time))')
//                    ->andWhere(['or', ['between', 'from_time', $from_time, $to_time], ['between', 'to_time', $from_time, $to_time]])
//                    ->all();

            $query = $this->find()
                    ->where('dcs_code=\'' . $this->dcs_code . '\' and shift_code=\'' . $this->shift_code . '\' and from_date=\'' . $this->from_date . '\' and to_date=\'' . $this->to_date . '\'  and ((\'' . $from_time . '\'  between from_time and to_time) OR (\'' . $to_time . '\' between from_time  and to_time))')
                    ->orWhere(['or', ['between', 'from_time', $from_time, $to_time], ['between', 'to_time', $from_time, $to_time]])
                    ->andfilterWhere(['!=', 'incentive_deduction_id', $this->incentive_deduction_id])
                    ->all();

            if (!empty($query)) {
                $this->addError('from_date', Yii::t('app/validation', 'Data is allready exist'));
            }
        } else {
            $this->addError('from_date', Yii::t('app/validation', 'Invalid Date Range'));
        }
    }

}
