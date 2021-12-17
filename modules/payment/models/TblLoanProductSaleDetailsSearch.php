<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblLoanProductSaleDetails;

/**
 * TblLoanProductSaleDetailsSearch represents the model behind the search form about `app\modules\payment\models\TblLoanProductSaleDetails`.
 */
class TblLoanProductSaleDetailsSearch extends TblLoanProductSaleDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_detail_code', 'product_code', 'entry_type', 'send_status', 'txfarmer_id'], 'integer'],
            [['dcs_code', 'union_code', 'member_code', 'sale_date_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'response_datetime', 'picked_datetime', 'resp_desc', 'data_inserted_from'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblLoanProductSaleDetails::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'sale_detail_code' => $this->sale_detail_code,
            'product_code' => $this->product_code,
            'sale_date_time' => $this->sale_date_time,
            'amount' => $this->amount,
            'entry_type' => $this->entry_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'send_status' => $this->send_status,
            'response_datetime' => $this->response_datetime,
            'picked_datetime' => $this->picked_datetime,
            'txfarmer_id' => $this->txfarmer_id,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'resp_desc', $this->resp_desc])
            ->andFilterWhere(['like', 'data_inserted_from', $this->data_inserted_from]);

        return $dataProvider;
    }
}
