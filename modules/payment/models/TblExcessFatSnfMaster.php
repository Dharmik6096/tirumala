<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_excess_fat_snf_master".
 *
 * @property integer $excess_fat_snf_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $mcc_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property string $created_by
 * @property string $created_date
 * @property string $update_by
 * @property string $updated_at
 * @property integer $is_active
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblExcessFatSnfMaster extends \app\models\ChildModel {

    public $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_excess_fat_snf_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'created_at', 'updated_at', 'is_active', 'created_by', 'update_by', 'fat', 'snf', 'from_shift', 'to_shift'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'fat', 'snf'], 'required', 'except' => ['importCsv']],
            [['from_shift', 'to_shift'], 'required', 'except' => ['deactivate']],
            [['bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'fat', 'snf'], 'required', 'on' => ['importCsv']],
            [['to_date'], 'validateToDate'],
            [['fat'], 'validateFatSnf'],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, TRUE);
                }, 'on' => ['importCsv']],
            [['from_date', 'to_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['from_date', 'to_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['from_date', 'to_date'], 'convertDate', 'on' => ['importCsv']],
            [['is_active'], 'default', 'value' => 1],
            [['from_shift', 'to_shift'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
            [['from_shift', 'to_shift'], 'integer', 'message' => Yii::t('app/validation', 'Please enter valid Shift Code.'), 'on' => ['importCsv']],
            [['dcs_code'], 'validateDcs'],
            [['dcs_code'], 'checkDateRange'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'excess_fat_snf_id' => Yii::t('app', 'Excess Fat Snf ID'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'update_by' => Yii::t('app', 'Update By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function validateFatSnf($attribute, $params) {
        if ($this->fat == 0 && $this->snf == 0) {
            $this->addError($attribute, 'Fat or SNF must be greater than zero.');
        }
    }

    public function validateDcs() {
        if (empty($this->getErrors())) {
            if (!empty($this->dcs_code)) {
                $dcs = new TblDcs();
                $dcs_code = $dcs->validDcs($this->dcs_code, $this->bmc_code);
                $dcs_code = $dcs->find()->select('dcs_code')->where(['or', ['dcs_code' => $dcs_code], ['dcs_code_ex' => $dcs_code], ['ref_code' => $dcs_code]])->andWhere(['is_active' => 1])->one();
                $this->dcs_code = !empty($dcs_code->dcs_code) ? $dcs_code->dcs_code : '';
            }

            if (empty($this->dcs_code)) {
                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS Code') . ' is invalid'));
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->from_date = Yii::$app->controls->view_date($this->from_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->from_date = '-';
        }
        try {
            $this->to_date = Yii::$app->controls->view_date($this->to_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->to_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? Yii::$app->controls->view_date($this->from_date, 'php:Y-m-d') : NULL;
            $this->to_date = !empty($this->to_date) ? Yii::$app->controls->view_date($this->to_date, 'php:Y-m-d') : NULL;
        }
    }

    public function checkDateRange() {
        $fromDate = $this->from_date = Yii::$app->formatter->asDate($this->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->from_shift);
        $toDate = $this->to_date = Yii::$app->formatter->asDate($this->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->to_shift);
        $condition = '((\'' . $fromDate . '\' between from_date  and to_date) OR (\'' . $toDate . '\' between from_date  and to_date) OR (from_date between \'' . $fromDate . '\' and  \'' . $toDate . '\') OR (to_date between \'' . $fromDate . '\' and \'' . $toDate . '\'))';

        $query = $this->find()
                ->where($condition)
                ->andWhere(['is_active' => 1, 'dcs_code' => $this->dcs_code]);
        if (!empty($this->excess_fat_snf_id)) {
            $query->andWhere(['!=', 'excess_fat_snf_id', $this->excess_fat_snf_id]);
        }
        $data = $query->all();

        if (!empty($data)) {
            foreach ($data as $record) {
                $from_date = date('d-m-Y', strtotime($record->from_date));
                $to_date = date('d-m-Y', strtotime($record->to_date));
                $this->addError('dcs_code', "Date Range Already Available in this range : $from_date to $to_date");
                return false;
            }
        }

        return true;
    }

}
