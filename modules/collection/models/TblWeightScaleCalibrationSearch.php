<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblWeightScaleCalibration;

/**
 * TblWeightScaleCalibrationSearch represents the model behind the search form about `app\modules\collection\models\TblWeightScaleCalibration`.
 */
class TblWeightScaleCalibrationSearch extends TblWeightScaleCalibration {

    public $actual_operator_qty, $manual_operator_qty, $from_date, $to_date, $from_shift, $to_shift, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ws_code', 'date_time_of_collection', 'shift_code', 'manual_quantity', 'actual_quantity', 'reference_measurement', 'remarks', 'sync_status', 'sync_timestamp', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date', 'from_shift', 'to_shift', 'bmc_ref_code', 'actual_operator_qty', 'manual_operator_qty'], 'safe'],
            [['manual_quantity', 'actual_quantity'], 'number'],
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
        $query = TblWeightScaleCalibration::find();
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

        $query->joinWith(['dcsCode', 'shiftCode', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_scale_calibration', 'tbl_weight_scale_calibration', 'tbl_weight_scale_calibration');

        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_scale_calibration.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        if (!empty($this->manual_quantity)) {
            $query->andFilterWhere([$this->manual_operator_qty, 'tbl_weight_scale_calibration.manual_quantity', $this->manual_quantity]);
        }

        if (!empty($this->actual_quantity)) {
            $query->andFilterWhere([$this->actual_operator_qty, 'tbl_weight_scale_calibration.actual_quantity', $this->actual_quantity]);
        }

        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_scale_calibration.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'ws_code', $this->ws_code])
                ->andFilterWhere(['like', 'reference_measurement', $this->reference_measurement]);

        return $dataProvider;
    }

}
