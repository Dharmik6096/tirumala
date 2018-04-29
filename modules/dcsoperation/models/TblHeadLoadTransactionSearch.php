<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblHeadLoadTransaction;

/**
 * TblHeadLoadTransactionSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblHeadLoadTransaction`.
 */
class TblHeadLoadTransactionSearch extends TblHeadLoadTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_load_transaction_code', 'from_km', 'from_qty', 'to_km', 'to_qty', 'value', 'created_at', 'deleted_at', 'updated_at', 'created_by', 'deleted_by', 'head_load_code', 'updated_by'], 'safe'],
//            [['from_km', 'from_qty', 'to_km', 'to_qty', 'value'], 'number'],
            [['is_delete'], 'integer'],
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
        $query = TblHeadLoadTransaction::find();

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
            'from_km' => $this->from_km,
            'from_qty' => $this->from_qty,
            'is_delete' => 0,
            'to_km' => $this->to_km,
            'to_qty' => $this->to_qty,
            'value' => $this->value,
        ]);

        $query->andFilterWhere(['like', 'head_load_transaction_code', $this->head_load_transaction_code])
            ->andFilterWhere(['like', 'head_load_code', $this->head_load_code]);

        return $dataProvider;
    }
}
