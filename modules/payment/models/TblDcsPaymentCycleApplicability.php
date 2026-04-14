<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblRateType;
use app\modules\dcsoperation\models\TblRateGenerateMethod;
use app\modules\collection\models\TblMilkCollection;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\payment\models\TblPaymentCycleApplicability;

/**
 * This is the model class for table "tbl_dcs_payment_cycle_applicability".
 *
 * @property integer $payment_cycle_applicabilty_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $is_lock
 * @property integer $data_lock
 * @property integer $created_by
 * @property integer $created_date
 * $@property integer dcs_payment_cycle_code
 */
class TblDcsPaymentCycleApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $dcs_name;
    public $rate_gen_method_code;
    public $rate_type;
    public $max_to_date;
    public $union_code;
    public $saveChildRecords = TRUE;

    public static function tableName() {
        return 'tbl_dcs_payment_cycle_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_code', 'payment_cycle_code'], 'safe'],
                [['dcs_code'], 'required', 'message' => 'You must select atleast one society.'],
                [['from_date', 'to_date', 'dcs_name', 'created_by', 'created_date'], 'safe'],
                [['is_lock', 'data_lock'], 'integer'],
                [['dcs_payment_cycle_code', 'data_lock_vsp'], 'safe'],
                [['dcs_code'], 'required', 'on' => ['androidsync']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_payment_cycle_code' => Yii::t('app', 'dcs_payment_cycle_code'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'is_lock' => Yii::t('app', 'Is Lock'),
            'data_lock' => Yii::t('app', 'Data Lock'),
        ];
    }

    /**
     * 
     * @return TblDcsPaymentCycleApplicabilityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPaymentCycleApplicabilityQuery(get_called_class());
    }

    //get relationship
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getSocietyPayment() {
        return $this->hasMany(TblSocietyPayment::className(), ['payment_cycle_applicabilty_code' => 'payment_cycle_applicabilty_code']);
    }

    public function getPaymentCycle() {
        return $this->hasOne(TblDcsPaymentCycle::className(), ['dcs_payment_cycle_code' => 'dcs_payment_cycle_code']);
    }

    /**
     * @return unique payment cycle date
     * @return applicablity date for dropdown     * 
     */
    public function uniqueCycle() {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['convert(varchar(20),from_date,105) as from_date', 'convert(varchar(20),to_date,105) as to_date'])->where(['is_lock' => 0])->distinct()->all(), function($model) {
                    return $model['from_date'] . ' to ' . $model['to_date'];
                }, function($model) {
                    return $model['from_date'] . ' to ' . $model['to_date'];
                });
    }

    public function paymentCycle($union_code) {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['from_date', 'to_date', 'dcs_payment_cycle_code'])->joinWith(['paymentCycle'])->where(['is_lock' => 0, 'data_lock' => 1, 'tbl_dcs_payment_cycle.union_code' => $union_code])->distinct()->all(), function($model) {
                    return $model['dcs_payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

    public function dcsPaymentCycle($dcs_code) {
        return \yii\helpers\ArrayHelper::map($this->find()->select(['from_date', 'to_date', 'dcs_payment_cycle_code'])->where(['is_lock' => 0, 'dcs_code' => $dcs_code])->distinct()->all(), function($model) {
                    return $model['dcs_payment_cycle_code'];
                }, function($model) {
                    return Yii::$app->controls->view_date($model['from_date']) . ' to ' . Yii::$app->controls->view_date($model['to_date']);
                });
    }

    public function dcsPaymentCycleAppCode($dcs_code) {
        $query = $this->find()->select(['payment_cycle_applicabilty_code'])->where(['is_lock' => 0, 'dcs_code' => $dcs_code])->distinct()->one();
        return $query['payment_cycle_applicabilty_code'];
    }

    public function getPaymentCycleDcs($id) {
        $cycle = TblDcsPaymentCycle::findOne($id);
        $dcs = $this->find()->select(['dcs_code', 'dcs_payment_cycle_code'])->where(['or', ['between', 'from_date', $cycle->from_date, $cycle->to_date],
                        ['between', 'to_date', $cycle->from_date, $cycle->to_date]])->all();
        return $dcs;
    }

    public function getPaymentCycleDcsWithGap($id) {
        $cycle = TblDcsPaymentCycle::findOne($id);
        $date = $cycle->from_date;
        $maxdate = date('Y-m-d', strtotime($date . ' -1 day'));
        $mdate = $cycle->to_date;
        $mindate = date('Y-m-d', strtotime($mdate . ' +1 day'));
        $subQuery = $this->find()->select(['dcs_code'])->groupBy(['dcs_code'])->having(['or', ['<', 'max(to_date)', $maxdate], ['>', 'min(from_date)', $mindate]])->asArray()->all();
        $dcs = $this->find()->select(['dcs_code', 'dcs_payment_cycle_code'])->where(['in', 'dcs_code', $subQuery])->all();
        return $dcs;
    }

    /**
     * @purpose get all society list which comes in given applicabilty range
     * @return applicablity date for dropdown     * 
     */
    public function societyList($cycle) {
        //        $collection_data = TblMilkCollection::find()->select(['dcs_code'])->distinct()->all();
//        $collection_data = ArrayHelper::getColumn($collection_data, 'dcs_code');
        $condition = ['dcs_payment_cycle_code' => $cycle, 'tbl_dcs.is_active' => 1];
        $list = $this->find()->select(['tbl_dcs_payment_cycle_applicability.dcs_code as dcs_code', 'dcs_name', 'data_lock', 'data_lock_vsp'])
                ->innerJoinWith('dcsCode')
                ->where($condition)
                ->asArray()
                ->all();

        //        $processed = new TblMemberPayment();
//        $processed= $processed->find()->select(['dcs_code'])->where(['dcs_payment_cycle_code' => $cycle])->all();
//        $processed = ArrayHelper::getColumn($processed, 'dcs_code');
        $processed = [];


        $list = ArrayHelper::toArray($list, [
                    'app\modules\payment\models\TblDcsPaymentCycleApplicability' => [
                        'dcs_code',
                        'dcs_name',
                        'data_lock',
                    ],
        ]);
        return ['processed' => $processed, 'list' => $list];

        //        return \yii\helpers\ArrayHelper::map($list, 'dcs_code', 'dcs_name');
    }

    /**
     * @purpose get date range from string
     * @return date range array    * 
     */
    public function dateRange($date) {
        return array_map(
                function($element) {
            return Yii::$app->formatter->asDate(trim($element), DATE_FORMAT);
        }, explode('to', $date));
    }

    public function getRateType() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type']);
    }

    public function getRateMethod() {
        return $this->hasOne(TblRateGenerateMethod::className(), ['code' => 'rate_gen_method_code']);
    }

    public function apiCurrentDcsPaymentCycle($dcs_code, $date) {
        return $this->find()
                        ->where(['is_lock' => 0, 'dcs_code' => $dcs_code])
                        ->andFilterWhere(['<=', 'from_date', $date])
                        ->andFilterWhere(['>=', 'to_date', $date])
                        ->one();
    }

    public function setTransactionData(&$model, $json, &$childModel) {
        $dcs = TblDcs::findOne(['dcs_code' => $model->dcs_code]);
        if (!$dcs) {
            $model->addError('dcs_code', "DCS not found.");
            return;
        }

        $cycleModel = TblPaymentCycle::find()->where(['union_code' => $dcs->union_code, 'from_date' => $model->from_date, 'to_date' => $model->to_date])->one();

        if (!$cycleModel) {
            $cycleModel = new TblPaymentCycle();
            $cycleModel->from_date = $model->from_date;
            $cycleModel->to_date = $model->to_date;
            $cycleModel->union_code = $dcs->union_code;
            $cycleModel->is_active = 1;
            $cycleModel->interval_value = 1;
            $cycleModel->from_shift = isset($json['from_shift']) ? $json['from_shift'] : 1;
            $cycleModel->to_shift = isset($json['to_shift']) ? $json['to_shift'] : 2;
            try {
                if (!$cycleModel->save()) {
                    $errors = [];
                    foreach ($cycleModel->getErrors() as $attr => $err) {
                        $errors[] = implode(", ", $err);
                    }
                    $model->addError('dcs_code', "Error saving Payment Cycle: " . implode("; ", $errors));
                    return;
                }
            } catch (\Throwable $e) {
                $model->addError('dcs_code', "Exception saving Payment Cycle: " . $e->getMessage());
                return;
            }
        }
        $appRecord = TblPaymentCycleApplicability::find()->where(['applicable_code' => $dcs->bmc_code, 'applicable_for' => 'BMC', 'applicable_type' => 'DCS'])
                ->andWhere('((\'' . $model->from_date . '\' between from_date and to_date) OR (\'' . $model->to_date . '\' between from_date and to_date) OR (from_date between \'' . $model->from_date . '\' and \'' . $model->to_date . '\') OR (to_date between \'' . $model->from_date . '\' and \'' . $model->to_date . '\'))')
                ->one();
        if ($appRecord) {
            $existingFrom = date('d-m-Y', strtotime($appRecord->from_date));
            $existingTo = date('d-m-Y', strtotime($appRecord->to_date));
            $errorMessage = "Already exists: The selected dates conflict with the period $existingFrom to $existingTo.";
            $model->addError('dcs_code', $errorMessage);
            return;
        }

        $newApp = new TblPaymentCycleApplicability();
        $newApp->from_date = $model->from_date;
        $newApp->to_date = $model->to_date;
        $newApp->applicable_code = $dcs->bmc_code;
        $newApp->applicable_for = 'BMC';
        $newApp->applicable_type = 'DCS';
        $newApp->payment_cycle_code = $model->payment_cycle_code = $cycleModel->payment_cycle_code;
        $newApp->union_code = $cycleModel->union_code;
        $childModel[] = $newApp;
    }

}
