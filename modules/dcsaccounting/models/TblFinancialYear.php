<?php

namespace app\modules\dcsaccounting\models;

use Yii;

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
 * @property integer $is_delete
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 *
 * @property TblLedgerBudgeting[] $tblLedgerBudgetings
 * @property TblLedgerBudgetingHistory[] $tblLedgerBudgetingHistories
 */
class TblFinancialYear extends \app\models\ChildModel {

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
            [['ending_date', 'starting_date', 'code'], 'required'],
            [['code'], 'unique'],
            [['code'], 'codeValidate'],
            [['ending_date', 'starting_date'], 'customValidate'],
            [['ending_date'], 'rangeValidate'],
            [['starting_date'], 'dateValidate'],
            [['ending_date', 'starting_date', 'code', 'flg_sentbox_entry', 'created_at', 'deleted_at', 'is_active', 'is_delete', 'sync_status', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'updated_by'], 'safe'],
            [['code'], 'string', 'max' => 15],
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

        if ($code != $this->code) {
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

            //$query = $this->find()->where('(("'.$this->starting_date.'"  betwseen DATE(starting_date) and DATE(ending_date)) OR ("'.$this->ending_date.'" between DATE(starting_date) and DATE(ending_date))) AND  is_active=1 AND is_delete=0');
            $query = $this->find()->where('(( DATE(starting_date) between  "' . $this->starting_date . '" and "' . $this->ending_date . '" ) OR (DATE(ending_date) between "' . $this->starting_date . '" and "' . $this->ending_date . '")) AND  is_active=1 AND is_delete=0');

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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'Financial Year ID'),
            'code' => Yii::t('app', 'Code'),
            'ending_date' => Yii::t('app', 'End Date'),
            'starting_date' => Yii::t('app', 'Start Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerBudgetings() {
        return $this->hasMany(TblLedgerBudgeting::className(), ['financial_year_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLedgerBudgetingHistories() {
        return $this->hasMany(TblLedgerBudgetingHistory::className(), ['financial_year_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblFinancialYearQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFinancialYearQuery(get_called_class());
    }

    public function getCurrentYear() {

        $data = $this->find()->select('id,code')->where(['is_active' => 1])->orderBy(['id' => SORT_DESC])->one();
        //$data = $this->find()->select('code')->where('is_active=1  and ("'.$date.'" between starting_date and ending_date) ')->one();
        return !empty($data) ? $data->code : '';
    }

    public function getYear() {

        $date = date('Y-m-d');
        $data = $this->find()->select('code')->where('is_active=1  and ("' . $date . '" between starting_date and ending_date) ')->one();
        return $data;
    }

    public function checkEdit() {
        $generalModel = new \app\models\GeneralModel();
        $valueOut = $generalModel->callSp('sp_delete_master_dcs_accounting', ['tbl_financial_year', '', '', '', '', $this->id, 'id']);
        return $valueOut;
    }

    public function getLatestYear() {

        $data = $this->find()->select('id,code')->where(['is_active' => 1])->orderBy(['id' => SORT_DESC])->limit(1)->all();

        $values = \yii\helpers\ArrayHelper::map($data, 'id', 'code');
        return $values;
    }

    public function getFinancialYear() {
        $data = $this->find()->where(['code' => $this->code])->one();
        return $data;
    }

}
