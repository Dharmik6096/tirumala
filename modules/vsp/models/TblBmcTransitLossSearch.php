<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBmcTransitLoss;

/**
 * TblBmcTransitLossSearch represents the model behind the search form about `app\modules\vsp\models\TblBmcTransitLoss`.
 */
class TblBmcTransitLossSearch extends TblBmcTransitLoss {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transit_loss_code', 'loss_applied_to'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'date_time_of_collection', 'shift_code', 'transporter_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['kg_fat', 'kg_snf', 'qty', 'amount', 'loss_amount'], 'number'],
            [['from_date', 'to_date', 'from_shift', 'to_shift'], 'safe']
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
        $query = TblBmcTransitLoss::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_transit_loss', 'tbl_bmc_transit_loss', 'tbl_bmc_transit_loss');

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'tbl_bmc_transit_loss.date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 23:59:59.000']]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'kg_fat', $this->kg_fat])
                ->andFilterWhere(['like', 'kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'loss_amount', $this->loss_amount])
                ->andFilterWhere(['like', 'tbl_bmc_transit_loss.loss_applied_to', $this->loss_applied_to]);

        return $dataProvider;
    }

}
