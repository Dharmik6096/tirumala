<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblMilkQualityType;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_purchase_rate_based".
 *
 * @property integer $rate_based_code
 * @property string $created_at
 * @property string $deleted_at
 * @property double $end_range
 * @property string $deduction_type
 * @property string $ref_type
 * @property double $fixed_point
 * @property double $value
 * @property double $kg_rate
 * @property integer $quality_param_code
 * @property integer $milk_quality_type_code
 * @property double $start_range
 * @property double $step
 * @property string $updated_at
 * @property integer $milk_type_code
 * @property string $created_by
 * @property string $formula_code
 * @property string $purchase_rate_code
 * @property string $updated_by
 * @property string $deleted_by
 * @property integer $rate_type_code
 *
 * @property TblAnimalType $milkTypeCode
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMilkQualityGrade $milkQualityTypeCode
 * @property TblPurchaseRateMaster $purchaseRateCode
 * @property User $updatedBy
 */
class TblPurchaseRateBased extends \app\models\ChildModel {

    public $quality_param_code_name;
    public $formula;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_purchase_rate_based';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_class'], 'default', 'value' => 'A'],
            [['milk_quality_type_code'], 'default', 'value' => 1],
            [['deduction_type', 'fixed_point', 'kg_rate', 'ref_type', 'step', 'value'], 'default', 'value' => '0'],
            [['rate_type_code'], 'unique', 'targetAttribute' => ['rate_type_code', 'quality_param_code', 'purchase_rate_code', 'milk_type_code'], 'on' => 'stellapps'],
            [['quality_param_code', 'deduction_type', 'ref_type', 'kg_rate'], 'required', 'on' => 'manualForm'],
            [['milk_type_code', 'end_range', 'start_range', 'rate_type_code'], 'required'],
            [['formula', 'kg_rate'], 'required', 'except' => ['excel', 'stellapps']],
            [['end_range', 'start_range'], 'number', 'min' => 0.1, 'max' => 99, 'numberPattern' => '/^\d+(.\d{1,1})?$/', 'message' => Yii::t('app/validation', 'Range should single decimal number.')],
            [['end_range'], 'customValidate'],
            [['rate_type_code'], 'RateTypeValidate'],
            [['fixed_point', 'value', 'step'], 'required', 'when' => function($model) {
                    return $model->ref_type == 1;
                }, 'whenClient' => "function (attribute, value) {  if($('#tblpurchaseratebased-0-ref_type').val()==1){return true;} }", 'on' => 'manualForm'],
            [['value'], 'required', 'when' => function($model) {
                    return $model->ref_type == 2;
                }, 'whenClient' => "function (attribute, value) {  if($('#tblpurchaseratebased-0-ref_type').val()==2){return true;} }", 'on' => 'manualForm'],
            [['ref_type'], 'RefTypeValidate', 'on' => 'manualForm'],
            [['start_range', 'end_range'], 'rangeValidate', 'on' => 'manualForm'],
            [['fixed_point'], 'fixedPointValidate', 'on' => 'manualForm'],
            [['created_at', 'step', 'updated_at', 'kg_rate', 'quality_param_code_name', 'formula_code', 'fixed_point', 'formula'], 'safe'],
            [['end_range', 'fixed_point', 'value', 'start_range'], 'number', 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "7" OR "7.5"')],
            [['quality_param_code', 'milk_quality_type_code', 'milk_type_code'], 'integer'],
            [['deduction_type', 'ref_type'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['purchase_rate_code'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code']],
//            [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code']],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    public function RefTypeValidate($attribute, $params) {
        if ($this->deduction_type > 0 && $this->ref_type <= 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Ref Type Should not be NA.'));
        } else if ($this->deduction_type <= 0 && $this->ref_type > 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Ref Type Should be NA.'));
        }
    }

    public function RateTypeValidate($attribute, $params) {

        if (!empty($this->rate_type_code) && !empty($this->milk_quality_type_code)) {

            $query = $this->find()->where('purchase_rate_code=\'' . $this->purchase_rate_code . '\'  and milk_type_code=\'' . $this->milk_type_code . '\'');
            $record = $query->one();
            if (!empty($record) && $this->rate_type_code != $record->rate_type_code) {
                $this->addError($attribute, Yii::t('app/validation', 'Other Rate Type is not allowed.'));
                return false;
            }
        }
    }

    public function customValidate($attribute, $params) {

        if (!empty($this->start_range) && !empty($this->end_range)) {

            if ($this->end_range < $this->start_range) {
                $this->addError($attribute, Yii::t('app/validation', 'End Rang cannot be less then Start Range.'));
                return false;
            }
        }
    }

    public function fixedPointValidate($attribute, $params) {

        if (!empty($this->fixed_point) && ($this->ref_type == 1)) {
            if ($this->deduction_type == 1 || $this->deduction_type == 3) {
                if ($this->fixed_point >= $this->start_range) {
                    $this->addError($attribute, Yii::t('app/validation', 'Fixed Point value must be less than Start Range.'));
                }
            } elseif ($this->deduction_type == 2 || $this->deduction_type == 4) {
                if ($this->fixed_point <= $this->end_range) {
                    $this->addError($attribute, Yii::t('app/validation', 'Fixed Point value must be grater than End Range.'));
                }
            }



            $query = $this->find()->where('purchase_rate_code=\'' . $this->purchase_rate_code . '\' and quality_param_code=\'' . $this->quality_param_code . '\' and milk_type_code=\'' . $this->milk_type_code . '\' and ((' . $this->fixed_point . ' between start_range and end_range))');
            $record = $query->count();
            if ($record == 0) {
                $this->addError($attribute, Yii::t('app/validation', 'Fixed Point value must be in between Start Range and End Range.'));
                return false;
            }
        }
    }

    public function rangeValidate($attribute, $params) {

        if (!empty($this->start_range) && !empty($this->end_range)) {

            $query = $this->find()->where('purchase_rate_code=\'' . $this->purchase_rate_code . '\' and quality_param_code=\'' . $this->quality_param_code . '\' and milk_type_code=\'' . $this->milk_type_code . '\'
                                                and  ((' . $this->start_range . '  between start_range and end_range) OR (' . $this->end_range . ' between start_range  and end_range))');

            $record = $query->one();
            if ($record) {
                $this->addError($attribute, Yii::t('app/validation', 'Can not use range in between of used range.'));
                return false;
            }
        }
    }

    public function ValidateManualRange($model) {
        $valid = TRUE;
        $message = '';
        for ($i = 0; $i < count($model); $i++) {
            $data = $this->find()->where(['milk_type_code' => $model[$i]->milk_type_code, 'purchase_rate_code' => $model[$i]->purchase_rate_code])->orderBy('quality_param_code,start_range')->all();
            $aqcnt = ArrayHelper::map($data, 'quality_param_code', 'quality_param_code');
            $qpcnt = explode('+', $model[$i]->rateType->rate_type);
            if (count($aqcnt) != count($qpcnt)) {
                $message = 'Line Missing for ' . $model[$i]->milkTypeCode->animal_type_name;
                break;
            }
            $num_array = [];
            $index = 0;
            for ($j = 0; $j < count($data); $j++) {
                $lowrange = $data[$j]->start_range;
                $highrannge = $data[$j]->end_range;
                for (; $lowrange <= $highrannge;) {
                    $num_array[$index] [] = $lowrange;
                    $lowrange = floatval(bcadd($lowrange, 0.1, 1));
                }
                if (!isset($data[$j + 1]) || $data[$j]->quality_param_code != $data[$j + 1]->quality_param_code) {
                    $index ++;
                }
            }
            for ($k = 0; $k < count($num_array); $k++) {
                for ($a = 1; $a < count($num_array[$k]); $a++) {
                    $diff = round(floatval($num_array[$k][$a]) - floatval($num_array[$k][$a - 1]), 1);
                    if ($diff != 0.1) {
                        $message = 'Range Missing for ' . $model[$i]->milkTypeCode->animal_type_name;
                        break;
                    }
                }
            }
        }
        if ($message != '') {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $message]);
            $valid = FALSE;
        }
        return [$valid, $message];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_based_code' => Yii::t('app', 'Rate Detail ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'end_range' => Yii::t('app', 'End Range'),
            'kg_rate' => Yii::t('app', 'KG Rate'),
            'deduction_type' => Yii::t('app', 'Deduction Type'),
            'ref_type' => Yii::t('app', 'Ref Type'),
            'fixed_point' => Yii::t('app', 'Fixed Point'),
            'value' => Yii::t('app', 'Value'),
            'formula_code' => Yii::t('app', 'Formula'),
            'formula' => Yii::t('app', 'Formula'),
            'step' => Yii::t('app', 'Step'),
            'quality_param_code_name' => Yii::t('app', 'Quality Param'),
            'quality_param_code' => Yii::t('app', 'Quality Param'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'start_range' => Yii::t('app', 'Start Range'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateFormula() {
        return $this->hasOne(TblFormulaMaster::className(), ['formula_code' => 'formula_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkQualityTypeCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQualityParamCode() {
        return $this->hasOne(TblQualityParam::className(), ['id' => 'quality_param_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getRateType() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type_code']);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateBasedQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPurchaseRateBasedQuery(get_called_class());
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblPurchaseRateBased::find();
        $query->orderBy('milk_type_code,quality_param_code,created_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->where(['purchase_rate_code' => $this->purchase_rate_code]);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'milk_quality_type_code' => $this->milk_quality_type_code,
        ]);

        $query->andFilterWhere(['like', 'kg_rate', $this->kg_rate])
                ->andFilterWhere(['like', 'end_range', $this->end_range])
                ->andFilterWhere(['like', 'quality_param_code', $this->quality_param_code])
                ->andFilterWhere(['like', 'start_range', $this->start_range])
                ->andFilterWhere(['like', 'milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['milk_quality_type_code' => $this->milk_quality_type_code,]);

        return $dataProvider;
    }

    public function createObject($rateType) {

        $names = explode(' ', $rateType);
        $count = count($names);
//        if($rateType!=1){
        for ($i = 0; $i < $count; $i++) {
            $models[$i] = new TblPurchaseRateBased();
        }
        return $models;
    }

    public function getDeductionType() {

        return [0 => 'NA', 1 => 'Value Addition', 2 => 'Value Deduction', 3 => 'Percentage Addition', 4 => 'Percentage Deduction'];
    }

    public function getRefType() {

        return [0 => 'NA', 1 => 'Fixed Point', 2 => 'Actual'];
    }

    public function getQualityParamId($name) {

        $q = new TblQualityParam();
        $name = $q->getMilkTypeId($name);
        return $name;
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(convert(bigint,rate_based_code)) as rate_based_code"])->one();
        return (int) $data['rate_based_code'] + 1;
    }

    public function setRateSession() {
        Yii::$app->session->set('start1', 0);
        Yii::$app->session->set('end1', 0);
        Yii::$app->session->set('start2', 0);
        Yii::$app->session->set('end2', 0);
        Yii::$app->session->set('rate1', 0);
        Yii::$app->session->set('rate2', 0);
        Yii::$app->session->set('formula', 0);
        Yii::$app->session->set('rate_type_code', 0);
        Yii::$app->session->set('rate_method', 0);
        Yii::$app->session->set('milk_type_code', 0);
        Yii::$app->session->set('milk_quality_type_code', NULL);
    }

    public function setRateRangeSession($prCode, $milk_type_code) {
        $query = $this->find()
                ->select([" MIN(start_range) as start_range", "MAX(end_range) as end_range"])
                ->where(['purchase_rate_code' => trim($prCode), 'milk_type_code' => $milk_type_code])
                ->groupBy('quality_param_code')
                ->all();


        foreach ($query as $k => $q) {
            $kgRate = $this->find()->where(['purchase_rate_code' => $prCode, 'start_range' => $q['start_range']])->one();
            Yii::$app->session->set('start' . ($k + 1), $q['start_range']);
            Yii::$app->session->set('end' . ($k + 1), $q['end_range']);
            Yii::$app->session->set('rate' . ($k + 1), $kgRate->kg_rate);
            Yii::$app->session->set('formula', $kgRate->rateFormula->formula_description);
        }

        Yii::$app->session->set('milk_quality_type_code', $kgRate->milk_quality_type_code);
    }

    public function checkRateParams($rateType) {

        $model = new TblQualityParam();
        $quality_param = $model->getParams();
        $param_list = null;
        foreach (explode('+', $rateType) as $param) {
            $param_list[] = array_search($param, $quality_param);
        }
        $check = \yii\helpers\ArrayHelper::map($this->find()->select('quality_param_code')->where(['purchase_rate_code' => $this->purchase_rate_code])->groupBy(['quality_param_code'])->all(), 'quality_param_code', 'quality_param_code');
        $diff = array_diff($param_list, $check);
        if ($diff) {
            return $quality_param[$diff];
        }
        return false;
    }

}
