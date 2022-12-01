<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursement;

/**
 * TblSchemeApplicationDisbursementSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApplicationDisbursement`.
 */
class TblSchemeApplicationDisbursementSearch extends TblSchemeApplicationDisbursement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disburse_id', 'application_id', 'originating_type'], 'integer'],
            [['disburse_date', 'disburse_by', 'payment_mode', 'bank_name', 'branch_name', 'party_name', 'party_relation', 'payment_ref_id', 'payment_detail', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['disburse_value'], 'number'],
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
        $query = TblSchemeApplicationDisbursement::find();

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
            'disburse_id' => $this->disburse_id,
            'application_id' => $this->application_id,
            'disburse_date' => $this->disburse_date,
            'disburse_value' => $this->disburse_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'disburse_by', $this->disburse_by])
            ->andFilterWhere(['like', 'payment_mode', $this->payment_mode])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'party_name', $this->party_name])
            ->andFilterWhere(['like', 'party_relation', $this->party_relation])
            ->andFilterWhere(['like', 'payment_ref_id', $this->payment_ref_id])
            ->andFilterWhere(['like', 'payment_detail', $this->payment_detail])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
