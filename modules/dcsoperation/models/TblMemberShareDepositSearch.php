<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberShareDeposit;

/**
 * TblMemberShareDepositSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberShareDeposit`.
 */
class TblMemberShareDepositSearch extends TblMemberShareDeposit {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['allotted_share', 'proposed_share', 'folio_no', 'total_share', 'share_amount', 'till_date', 'member_share_deposit_id', 'originating_type', 'member_code', 'originating_org_code', 'originating_org_type', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
        $query = TblMemberShareDeposit::find();

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
        if (!empty($this->till_date)) {
            $query->andFilterWhere(['CAST(till_date as date)' => date('Y-m-d', strtotime($this->till_date))]);
        }
        $query->andFilterWhere(['like', 'allotted_share', $this->allotted_share])
                ->andFilterWhere(['like', 'proposed_share', $this->proposed_share])
                ->andFilterWhere(['like', 'total_share', $this->total_share])
                ->andFilterWhere(['like', 'share_amount', $this->share_amount])
                ->andFilterWhere(['like', 'folio_no', $this->folio_no])
                ->andFilterWhere(['like', 'member_code', $this->member_code]);
        return $dataProvider;
    }

}
