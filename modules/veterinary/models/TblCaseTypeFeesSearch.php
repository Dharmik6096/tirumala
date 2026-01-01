<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblCaseTypeFees;

/**
 * TblCaseTypeFeesSearch represents the model behind the search form about `app\modules\veterinary\models\TblCaseTypeFees`.
 */
class TblCaseTypeFeesSearch extends TblCaseTypeFees {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['amount'], 'number'],
            [['case_type_id', 'amount', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblCaseTypeFees::find();

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
        $query->joinWith(['caseType']);

        // grid filtering conditions
        $query->andFilterWhere([
            'amount' => $this->amount,
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
        ]);

        $query->andFilterWhere(['like', 'tbl_case_type.case_type_name', $this->case_type_id]);

        return $dataProvider;
    }

}
