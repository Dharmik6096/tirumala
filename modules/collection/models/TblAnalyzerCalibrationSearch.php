<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblAnalyzerCalibration;

/**
 * TblAnalyzerCalibrationSearch represents the model behind the search form about `app\modules\collection\models\TblAnalyzerCalibration`.
 */
class TblAnalyzerCalibrationSearch extends TblAnalyzerCalibration {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['analyzer_calibration_code', 'date_time_of_calibration', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['shift_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['fat_offset', 'snf_offset', 'water_offset'], 'number'],
            [['from_date', 'to_date'], 'safe'],
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
        $query = TblAnalyzerCalibration::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
//            $from_date .=' ';
            $query->andFilterWhere(['>=', 'cast(date_time_of_calibration as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
//            $to_date .=' ';
            $query->andFilterWhere(['<=', 'cast(date_time_of_calibration as date)', $to_date]);
        }
        if (!empty($this->date_time_of_calibration))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_calibration, 126)', date('Y-m-d', strtotime($this->date_time_of_calibration))]);


        $query->andFilterWhere([
            'milk_type_code' => $this->milk_type_code
        ]);

        return $dataProvider;
    }

}
