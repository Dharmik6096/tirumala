<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHeadDetail;

/**
 * TblBillHeadDetailSearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadDetail`.
 */
class TblBillHeadDetailSearch extends TblBillHeadDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_detail_code', 'payment_cycle_code', 'is_installment', 'is_active'], 'integer'],
            [['union_code', 'bill_head_code', 'dcs_code', 'amount', 'no_installment', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['customer_type', 'customer_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
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
        $query = TblBillHeadDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'bill_head_detail_code' => $this->bill_head_detail_code,
            'payment_cycle_code' => $this->payment_cycle_code,
            'is_installment' => $this->is_installment,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'no_installment', $this->no_installment])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblBillHeadDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this);
        $query->andWhere([
            'bmc_code' => $this->bmc_code
        ]);

        return $dataProvider;
    }

}
