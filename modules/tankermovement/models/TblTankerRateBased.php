<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblMilkQualityType;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_tanker_rate_based".
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
 * @property string $tanker_rate_code
 * @property string $updated_by
 * @property string $deleted_by
 * @property integer $rate_type_code
 *
 * @property TblAnimalType $milkTypeCode
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMilkQualityGrade $milkQualityTypeCode
 * @property TblTankerRateMaster $purchaseRateCode
 * @property User $updatedBy
 */
class TblTankerRateBased extends \app\models\ChildModel {

    public $purchase_rate;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tanker_rate_based';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tanker_rate_code', 'milk_quality_type_code', 'milk_type_code', 'rate_type_code'], 'required'],
            [['base_rate', 'std_fat', 'std_snf'], 'required', 'except' => 'excel'],
            [['fat_ratio', 'snf_ratio', 'std_fat', 'std_snf', 'qty_rate', 'fat_rate', 'snf_rate'], 'number', 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "7" OR "7.5"'), 'except' => 'excel'],
            [['milk_quality_type_code', 'milk_type_code'], 'integer'],
            ['rate_type_code', 'unique', 'targetAttribute' => ['milk_quality_type_code', 'milk_type_code', 'tanker_rate_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Tanker Rate for Same milk type & quality is available.')],
                //  [['rate_type_code'], 'rateTypeValidate'],
        ];
    }

    public function RateTypeValidate($attribute, $params) {

        if (!empty($this->rate_type_code) && !empty($this->milk_quality_type_code)) {
            $query = $this->find()->where('tanker_rate_code=\'' . $this->tanker_rate_code . '\'  and milk_type_code=\'' . $this->milk_type_code . '\'');
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

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_based_code' => Yii::t('app', 'Rate Detail ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'base_rate' => Yii::t('app', 'Base Rate'),
            'fat_rate' => Yii::t('app', 'Fat Rate'),
            'snf_rate' => Yii::t('app', 'SNF Rate'),
            'qty_rate' => Yii::t('app', 'Qty Rate'),
            'fat_ratio' => Yii::t('app', 'Fat Ratio'),
            'snf_ratio' => Yii::t('app', 'SNF Ratio'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'rate_type_code' => Yii::t('app', 'Rate Type'),
            'std_fat' => Yii::t('app', 'Std FAT'),
            'std_snf' => Yii::t('app', 'Std SNF'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'tanker_rate_code' => Yii::t('app', 'Tanker Rate'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getPurchaseRateCode() {
        return $this->hasOne(TblTankerRate::className(), ['tanker_rate_code' => 'tanker_rate_code']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkQualityTypeCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateCode() {
        return $this->hasOne(TblTankerRate::className(), ['tanker_rate_code' => 'tanker_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateBasedQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblTankerRateBasedQuery(get_called_class());
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblTankerRateBased::find();
        $query->orderBy('milk_type_code,milk_quality_type_code,created_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->where(['tanker_rate_code' => $params['id']]);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function createObject($rateType) {

        $names = explode(' ', $rateType);
        $count = count($names);
//        if($rateType!=1){
        for ($i = 0; $i < $count; $i++) {
            $models[$i] = new TblTankerRateBased();
        }
        return $models;
    }

    public function getDeductionType() {

        return [0 => 'NA', 1 => 'Value Addition', 2 => 'Value Deduction', 3 => 'Percentage Addition', 4 => 'Percentage Deduction'];
    }

    public function getRefType() {

        return [0 => 'NA', 1 => 'Fixed Point', 2 => 'Actual'];
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
                ->where(['tanker_rate_code' => trim($prCode), 'milk_type_code' => $milk_type_code])
                ->groupBy('quality_param_code')
                ->all();

        foreach ($query as $k => $q) {
            $kgRate = $this->find()->where(['tanker_rate_code' => $prCode, 'start_range' => $q['start_range']])->one();
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
        $check = \yii\helpers\ArrayHelper::map($this->find()->select('quality_param_code')->where(['tanker_rate_code' => $this->tanker_rate_code])->groupBy(['quality_param_code'])->all(), 'quality_param_code', 'quality_param_code');
        $diff = array_diff($param_list, $check);
        if ($diff) {
            return $quality_param[$diff];
        }
        return false;
    }
}
