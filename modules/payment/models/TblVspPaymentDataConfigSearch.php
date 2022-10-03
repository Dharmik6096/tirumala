<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPaymentDataConfig;

/**
 * TblVspPaymentDataConfigSearch represents the model behind the search form about `app\modules\payment\models\TblVspPaymentDataConfig`.
 */
class TblVspPaymentDataConfigSearch extends TblVspPaymentDataConfig {

    public $from_date, $to_date, $from_shift, $to_shift, $ref_code, $dcs_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vsp_payment_data_config_code', 'ref_code', 'dcs_name', 'from_date', 'to_date', 'from_shift', 'to_shift', 'shift_code', 'originating_type', 'date_time_of_collection', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblVspPaymentDataConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vsp_payment_data_config', 'tbl_vsp_payment_data_config', 'tbl_vsp_payment_data_config');

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'vsp_payment_data_config_code' => $this->vsp_payment_data_config_code,
        ]);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'shift_code', $this->shift_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'originating_type', $this->originating_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
