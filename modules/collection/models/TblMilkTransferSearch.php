<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkTransfer;

/**
 * TblMilkTransferSearch represents the model behind the search form about `app\modules\collection\models\TblMilkTransfer`.
 */
class TblMilkTransferSearch extends TblMilkTransfer {

    public $transfer_types;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_transfer_code', 'transaction_id', 'from_date', 'to_date', 'union_code', 'source_code', 'destination_code', 'vehicle_no', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'transfer_types'], 'safe'],
            [['from_shift', 'to_shift', 'transfer_type', 'originating_type'], 'integer'],
            [['fat', 'snf', 'qty', 'temp'], 'number'],
            [['transfer_types'], 'required', 'on' => ['indexSearch']],
            [['conductivity', 'ph_value', 'other_reading', 'freezing_point', 'salt', 'adt_value', 'adt_param', 'lactose', 'density', 'protein', 'water', 'clr'], 'safe'],
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
        $query = TblMilkTransfer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transfer_type' => $this->transfer_types,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_transfer');
        if ($this->transfer_types == 1) {
            if (Yii::$app->session->get('BMC') !== '')
                $query->andFilterWhere(['tbl_milk_transfer.destination_code' => explode(',', Yii::$app->session->get('BMC'))]);
        } elseif ($this->transfer_types == 0) {
            if (Yii::$app->session->get('BMC') !== '')
                $query->andFilterWhere(['tbl_milk_transfer.source_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (!empty($this->from_date))
            $query->andFilterWhere(['CAST(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['CAST(to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);

        $query->andFilterWhere(['like', 'milk_transfer_code', $this->milk_transfer_code])
                ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
                ->andFilterWhere(['like', 'source_code', $this->source_code])
                ->andFilterWhere(['like', 'destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'fat', $this->fat])
                ->andFilterWhere(['like', 'snf', $this->snf])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'temp', $this->temp])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
