<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblRateClass;
use app\modules\organisation\models\TblMccPlant;
use app\modules\dcsoperation\models\TblSchemeRateMcc;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_scheme_rate".
 *
 * @property integer $scheme_rate_code
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $rtpl
 * @property string $rate_class
 * @property integer $is_mcc_wise_rate
 * @property string $description
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeRate extends \app\models\ChildModel {

    public $mcc_plant_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'rtpl'], 'required'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'scheme_rate_code', 'is_active', 'is_member_rate'], 'safe'],
                [['from_shift', 'to_shift', 'is_mcc_wise_rate', 'originating_type'], 'integer'],
                [['rtpl'], 'number'],
                [['rate_class', 'union_code'], 'string', 'max' => 3],
                [['description'], 'string', 'max' => 255],
                [['from_shift'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['from_shift' => 'id'], 'on' => 'importCsv'],
                [['to_shift'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['to_shift' => 'id'], 'on' => 'importCsv'],
//                [['rate_class'], 'exist', 'skipOnError' => true, 'targetClass' => TblRateClass::className(), 'targetAttribute' => ['rate_class' => 'rate_class_code'], 'on' => 'importCsv'],
            [['is_mcc_wise_rate'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'is_type');
                }, 'on' => 'importCsv'],
//                [['rate_class'], function ($attribute, $params) {
//                    Yii::$app->general->validateGlobalData($this, $attribute, 'rate_class');
//                }, 'on' => 'importCsv'],
            [['from_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['from_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['from_date'], 'convertDate', 'on' => ['importCsv']],
                [['to_date'], 'convertDateDotTo', 'on' => ['importCsv']],
                [['to_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['to_date'], 'convertDateTo', 'on' => ['importCsv']],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => 'importCsv'],
//                [['mcc_plant_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
//                    return $model->is_mcc_wise_rate == 1;
//                }, 'whenClient' => "function (attribute, value) {  if($('#tblschemerate-is_mcc_wise_rate').val()==1){return true;} }"],
            [['mcc_plant_code'], 'validateMcc', 'on' => 'importCsv'],
                [['union_code'], 'validateUnionCode', 'on' => 'importCsv'],
//                [['to_date'], 'validateDateRange'],
            [['is_mcc_wise_rate'], 'default', 'value' => 0],
                [['rate_class'], 'default', 'value' => '0'],
                [['is_active'], 'default', 'value' => '1'],
                [['to_date'], 'validateToDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Id'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'rate_class' => Yii::t('app', 'Rate Class'),
            'is_mcc_wise_rate' => Yii::t('app', 'Mcc Wise Rate'),
            'description' => Yii::t('app', 'Description'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'is_member_rate' => Yii::t('app', 'Is Member Rate'),
        ];
    }

    public function getFromShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getRateClass() {
        return $this->hasOne(TblRateClass::className(), ['rate_class_code' => 'rate_class']);
    }

    public function validateDateRange($attribute, $params) {
        $this->from_date = date('Y-m-d', strtotime($this->from_date)) . ' ' . \Yii::$app->general->getshift($this->from_shift);
        $this->to_date = date('Y-m-d', strtotime($this->to_date)) . ' ' . \Yii::$app->general->getshift($this->to_shift);


        $dateData = $this->find()
                ->where('union_code=\'' . $this->union_code . '\'')
                ->andWhere('((\'' . $this->from_date . '\'  between from_date and to_date) OR (\'' . $this->to_date . '\' between from_date  and to_date) OR (from_date between \'' . $this->from_date . '\' and  \'' . $this->to_date . '\') OR (to_date between \'' . $this->from_date . '\' and \'' . $this->to_date . '\'))')
                ->andfilterWhere(['!=', 'scheme_rate_code', $this->scheme_rate_code])
                ->all();

        if (!empty($dateData)) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
        if ($this->from_date > $this->to_date) {
            $this->addError($attribute, Yii::t('app/validation', 'Date Range is invalid'));
            return false;
        }
    }

    public function validateMcc($attribute, $params) {
        if ($this->is_mcc_wise_rate) {
            $data = explode(",", $this->mcc_plant_code);
            foreach ($data as $value) {
                $this->mcc_plant_code = $value;
                if (empty($this->mccCode)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Mcc Plant Code is invalid'));
                    return false;
                }
            }
        }
    }

    public function setChildTable($model, &$modelSave, &$errors) {
        if ($model->is_mcc_wise_rate) {
            $data = explode(",", $model->mcc_plant_code);
            $i = 1;
            foreach ($data as $value) {
                $mccModel = new TblSchemeRateMcc();
                $mccModel->scheme_rate_mcc_code = Yii::$app->general->getCodeAutoIncrement($mccModel, $i);
                $mccModel->scheme_rate_code = $model->scheme_rate_code;
                $mccModel->mcc_plant_code = trim($value);
                $mccModel->union_code = $model->union_code;
                $i++;
                array_push($modelSave, $mccModel);
                if (!$mccModel->validate()) {
                    $errors[] = $mccModel->getErrors();
                }
            }
        }
    }

    public function validateUnionCode($attribute, $params) {
        if (!empty($this->union_code)) {
            $unions = explode(',', Yii::$app->session->get('Unions'));
            if (!(in_array($this->union_code, $unions))) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->union_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->from_date = Yii::$app->controls->view_date($this->from_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->from_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? Yii::$app->controls->view_date($this->from_date, 'php:Y-m-d') . ' ' . \Yii::$app->general->getshift($this->from_shift) : NULL;
        }
    }

    public function convertDateDotTo() {
        try {
            $this->to_date = Yii::$app->controls->view_date($this->to_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->to_date = '-';
        }
    }

    public function convertDateTo() {
        if (empty($this->getErrors())) {
            $this->to_date = Yii::$app->controls->view_date($this->to_date, 'php:Y-m-d') . ' ' . \Yii::$app->general->getshift($this->to_shift);
        }
    }

    public function getRateChartList($union_code) {
        $data = $this->find()->where(['union_code' => $union_code])->orderBy('from_date DESC')->all();
        return ArrayHelper::map($data, 'scheme_rate_code', function($data) {
                    return !empty($data->description) ? $data->scheme_rate_code . ' (' . $data->description . ')' : $data->scheme_rate_code;
                });
    }

    public function getDeactiveRecords($limit) {
        $date = date('Y-m-d');

        return $query = $this->find()
                ->where(['is_active' => 1])
                ->andWhere(['<', 'cast(to_date as date)', $date])
                ->limit($limit)
                ->all();
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->from_date) && !empty($this->to_date)) {
            if ($this->to_date < $this->from_date) {
                $this->addError($attribute, Yii::t('app/validation', 'To Date must be greater than From Date'));
                return false;
            }
        }
    }

}
