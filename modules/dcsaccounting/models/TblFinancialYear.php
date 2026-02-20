<?php

namespace app\modules\dcsaccounting\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;
use Yii;
use yii\base\UserException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_financial_year".
 *
 * @property integer $id
 * @property string $code
 * @property string $ending_date
 * @property integer $is_active
 * @property string $starting_date
 * @property string $flg_sentbox_entry
 * @property string $created_at
 * @property string $deleted_at
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 */
class TblFinancialYear extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_financial_year';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'created_at', 'created_by', 'ending_date', 'is_active', 'starting_date', 'updated_at', 'updated_by'], 'safe'],
            [['ending_date', 'starting_date', 'code'], 'required'],
            [['ending_date', 'starting_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['ending_date', 'starting_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['ending_date', 'starting_date'], 'convertDate', 'on' => ['importCsv']],
            [['code'], 'unique'],
            [['code'], 'codeValidate'],
            [['ending_date', 'starting_date'], 'customValidate'],
            [['ending_date'], 'rangeValidate'],
            [['starting_date'], 'dateValidate'],
            [['code'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'Financial Year ID'),
            'code' => Yii::t('app', 'Code'),
            'ending_date' => Yii::t('app', 'End Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'starting_date' => Yii::t('app', 'Start Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function codeValidate($attribute) {
        if (!empty($this->$attribute)) {
            $exp = explode('-', $this->$attribute);
            if (empty($exp[0]) || (empty($exp[1]) || ($exp[1] == '__'))) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is not valid.'));
                return false;
            }
        }
    }

    public function isLeapYear($year) {
        return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0)));
    }

    public function dateValidate($attribute) {
        $startDate = new \DateTime($this->starting_date);
        $endDate = new \DateTime($this->ending_date);
        $endYear = date('Y', strtotime($this->ending_date));
        $startYear = date('Y', strtotime($this->starting_date));
        $leapYear = $this->isLeapYear($endYear);
        $days = $endDate->diff($startDate)->days;
        $code = $startYear . '-' . substr($endYear, 2, 4);
        $codeFY = 'FY' . $code;
        if ($code != $this->code && $codeFY != $this->code) {
            $this->addError('starting_date', Yii::t('app/validation', 'Please select date as per Code.'));
            return false;
        } else if ($leapYear && $days != '365') {
            $this->addError('starting_date', Yii::t('app/validation', 'Maximum 1 year difference required.'));
            return false;
        } else if ($days != '364' && !$leapYear) {
            $this->addError('starting_date', Yii::t('app/validation', ' Maximum 1 year difference required.'));
            return false;
        }
    }

    public function customValidate($attribute) {
        if (!empty($this->$attribute)) {
            $startDate = date('Y-m-d', strtotime($this->starting_date));
            $endDate = date('Y-m-d', strtotime($this->ending_date));
            $query = $this->find()->where('(( starting_date between :start1 and :end1 ) OR ( ending_date between :start2 and :end2 )) AND  is_active=1', [':start1' => $startDate, ':end1' => $endDate, ':start2' => $startDate, ':end2' => $endDate]);
            if (Yii::$app->controller->action->id == 'update') {
                $query->andWhere(['<>', 'id', $this->id]);
            }
            $record = $query->count();

            if ($record != 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' is conflicting.'));
            }
        }
    }

    public function rangeValidate($attribute, $params) {
        if (!empty($this->starting_date) && !empty($this->ending_date)) {
            if (strtotime($this->ending_date) < strtotime($this->starting_date)) {
                $this->addError($attribute, Yii::t('app/validation', 'Ending Date can not be less then Starting Date.'));
                return false;
            } else if (strtotime($this->ending_date) == strtotime($this->starting_date)) {
                $this->addError($attribute, Yii::t('app/validation', 'Starting Date and Ending Date cannot be same.'));
                return false;
            }
        }
    }

    public function getCurrentYear() {
        $data = $this->find()->select('id,code')->where(['is_active' => 1])->orderBy(['id' => SORT_DESC])->one();
        return !empty($data) ? $data->code : '';
    }

    public function getYear() {
        return $this->find()->select('code')->where('is_active=1  and (:date between starting_date and ending_date) ', [':date' => date('Y-m-d')])->one();
    }

    public function getLatestYear() {
        $data = $this->find()->select(['id', 'code'])->where(['is_active' => 1])->orderBy(['id' => SORT_DESC])->one();
        return ArrayHelper::map([$data], 'id', 'code');
    }

    public function getFinancialYear() {
        return $this->find()->where(['code' => $this->code])->one();
    }

    public function convertDateDot($attribute) {
        try {
            $this->$attribute = Yii::$app->controls->view_date($this->$attribute, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->$attribute = '-';
        }
    }

    public function convertDate($attribute) {
        if (empty($this->getErrors())) {
            $this->$attribute = !empty($this->$attribute) ? Yii::$app->controls->view_date($this->$attribute, 'php:Y-m-d') : NULL;
            $this->code = trim($this->code);
            if (stripos($this->code, 'FY') !== 0) {
                $this->code = 'FY' . $this->code;
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code, '', FALSE, 2);
                $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code, '', FALSE, 2);
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, 'DELETE', $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

}
