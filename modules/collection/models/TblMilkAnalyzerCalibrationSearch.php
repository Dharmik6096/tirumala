<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkAnalyzerCalibration;

/**
 * TblMilkAnalyzerCalibrationSearch represents the model behind the search form about `app\modules\collection\models\TblMilkAnalyzerCalibration`.
 */
class TblMilkAnalyzerCalibrationSearch extends TblMilkAnalyzerCalibration {

    public $manual_fat_operator, $manual_snf_operator, $actual_fat_operator, $actual_snf_operator, $from_date, $to_date, $from_shift, $to_shift, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code', 'milk_type_code'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'milk_analyser_type_code', 'date_time_of_collection', 'shift_code', 'manual_fat', 'actual_fat', 'manual_snf', 'actual_snf', 'milk_type_code', 'remarks', 'sync_status', 'sync_timestamp', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'manual_fat_operator', 'manual_snf_operator', 'actual_fat_operator', 'actual_snf_operator', 'from_date', 'to_date', 'from_shift', 'to_shift', 'bmc_ref_code'], 'safe'],
            [['manual_fat', 'actual_fat', 'manual_snf', 'actual_snf'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblMilkAnalyzerCalibration::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'shiftCode', 'milkTypeCode', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_analyzer_calibration', 'tbl_milk_analyzer_calibration', 'tbl_milk_analyzer_calibration');

        if (!empty($this->manual_fat)) {
            $query->andFilterWhere([$this->manual_fat_operator, 'tbl_milk_analyzer_calibration.manual_fat', $this->manual_fat]);
        }
        if (!empty($this->manual_snf)) {
            $query->andFilterWhere([$this->manual_snf_operator, 'tbl_milk_analyzer_calibration.manual_snf', $this->manual_snf]);
        }
        if (!empty($this->actual_fat)) {
            $query->andFilterWhere([$this->actual_fat_operator, 'tbl_milk_analyzer_calibration.actual_fat', $this->actual_fat]);
        }
        if (!empty($this->actual_snf)) {
            $query->andFilterWhere([$this->actual_snf_operator, 'tbl_milk_analyzer_calibration.actual_snf', $this->actual_snf]);
        }
        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_milk_analyzer_calibration.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        $query->andFilterWhere(['like', 'milk_analyser_type_code', $this->milk_analyser_type_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code]);

        return $dataProvider;
    }

}
