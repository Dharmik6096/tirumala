<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMccShiftEndSummary;

/**
 * TblMccShiftEndSummarySearch represents the model behind the search form about `app\modules\collection\models\TblMccShiftEndSummary`.
 */
class TblMccShiftEndSummarySearch extends TblMccShiftEndSummary {

    public $operator_snf, $operator_fat, $operator_quantity, $from_date, $to_date, $from_shift, $to_shift, $ref_code, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_shift_end_summary_code', 'shift_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['date_time_of_collection', 'shift_code', 'milk_type_code', 'quantity', 'fat', 'snf', 'p_quantity', 'p_fat', 'p_snf', 'd_quantity', 'd_fat', 'd_snf', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date', 'from_shift', 'to_shift', 'ref_code', 'bmc_ref_code', 'operator_snf', 'operator_fat', 'operator_quantity'], 'safe'],
            [['quantity', 'fat', 'snf', 'p_quantity', 'p_fat', 'p_snf', 'd_quantity', 'd_fat', 'd_snf'], 'number'],
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
        $query = TblMccShiftEndSummary::find();

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

        $query->joinWith(['dcsCode', 'shiftCode', 'bmcCode', 'milkTypeCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_shift_end_summary', 'tbl_mcc_shift_end_summary', 'tbl_mcc_shift_end_summary');

        if (!empty($this->quantity)) {
            $query->andFilterWhere([$this->operator_quantity, 'tbl_mcc_shift_end_summary.quantity', $this->quantity]);
        }
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_mcc_shift_end_summary.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_mcc_shift_end_summary.snf', $this->snf]);
        }


        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_mcc_shift_end_summary.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        $query->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code]);

        return $dataProvider;
    }

}
