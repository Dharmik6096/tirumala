<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_payment_cycle".
 *
 * @property integer $payment_cycle_code
 * @property string $union_code
 * @property integer $interval_value
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property integer $is_active
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentCycle extends \app\models\ChildModel {

    public $federation_code;
    public $check_month;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_cycle';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'from_shift', 'to_shift', 'interval_value', 'union_code'], 'required'],
            ['interval_value', 'match', 'pattern' => '/^[0-9]+$/', 'message' => Yii::t('app', 'Interval Value must be integer.')],
            [['union_code', 'from_shift', 'to_shift', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by'], 'safe'],
            [['interval_value', 'is_active', 'originating_type'], 'safe'],
            [['from_date', 'to_date', 'created_at', 'updated_at', 'check_month', 'federation_code'], 'safe'],
            [['to_date'], 'customValidate'],
            [['from_date', 'to_date'], 'unique', 'targetAttribute' => ['from_date', 'to_date', 'union_code'], 'message' => 'Payemnt cycle already exist for same time period'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'union_code' => Yii::t('app', 'Union'),
            'interval_value' => Yii::t('app', 'Interval Value'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function customValidate($attribute, $params) {

        if (!empty($this->from_date) && !empty($this->to_date)) {

            if ($this->to_date < $this->from_date) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater Than From Date.'));
                return false;
            }
            if ($this->from_date == $this->to_date) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date and Shift cannot be the same as From Date and Shift.'));
                return false;
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function disableDelete() {
        $app = TblPaymentCycleApplicability::find()->where(['payment_cycle_code' => $this->payment_cycle_code])->one();
        $next = $this->getNextCycleCode($this->payment_cycle_code);
        if (!empty($app) || ($next != 0))
            return false;
        else
            return true;
    }

    public function getNextCycleCode($code, $dcsCode = '', $customer_type = '', $for = '', &$appCode = '') {
        if (!empty($code) && $code !== 0) {
            $currCycle = $this->find()->where(['payment_cycle_code' => $code])->one();
            $date = $currCycle->to_date;
            $new_date = date("Y-m-d H:i:s", strtotime($date . '+12 hours'));
            $nextCycle = $this->find()->select('payment_cycle_code')->where(['from_date' => $new_date]);
            if (empty($dcsCode)) {
                $nextCycle = $nextCycle->one();
            } else {
                $nextCycle = TblPaymentCycleApplicability::find()->select('payment_cycle_code,payment_cycle_applicabilty_code')->where(['from_date' => $new_date, 'applicable_code' => $dcsCode, 'applicable_type' => $customer_type, 'applicable_for' => $for])->one();
                if (!empty($nextCycle)) {
                    $appCode = $nextCycle->payment_cycle_applicabilty_code;
                }
            }
        } else
            $nextCycle = '';
        return empty($nextCycle) ? '0' : $nextCycle->payment_cycle_code;
    }

    public function getFromShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function UnionPaymentCycles($union_code) {
        $query = $this->find()->select(['from_date', 'to_date', 'payment_cycle_code'])->distinct()
                ->where(['union_code' => $union_code])
                ->andWhere(['<', 'from_date', date('Y-m-d')]);

        $data = $query->orderBy('from_date DESC')->all();
        return \yii\helpers\ArrayHelper::map($data, function($model) {
                    return $model['payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

}
