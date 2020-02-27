<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspOutstanding;

/**
 * TblVspOutstandingSearch represents the model behind the search form about `app\modules\payment\models\TblVspOutstanding`.
 */
class TblVspOutstandingSearch extends TblVspOutstanding {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_outstanding_code', 'payment_cycle_code'], 'safe'],
            [['union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['hold_amount', 'due_amount', 'plant_code', 'mcc_code', 'bmc_code', 'customer_type', 'customer_code'], 'safe'],
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
        $query = TblVspOutstanding::find();

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
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this);

        // grid filtering conditions
        $query->andFilterWhere([
            'hold_amount' => $this->hold_amount,
            'due_amount' => $this->due_amount,
        ]);

        return $dataProvider;
    }

}
