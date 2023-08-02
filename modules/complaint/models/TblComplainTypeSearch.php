<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplainType;

/**
 * TblComplainTypeSearch represents the model behind the search form about `app\modules\complaint\models\TblComplainType`.
 */
class TblComplainTypeSearch extends TblComplainType {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_escalation_code', 'complain_type_code', 'complain_type', 'is_active', 'complain_for', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblComplainType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['complainEscalationCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_complain_type.complain_type_code' => $this->complain_type_code,
            'tbl_complain_type.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_complain_type.complain_type', $this->complain_type])
                ->andFilterWhere(['like', 'tbl_complain_type.complain_for', $this->complain_for])
                ->andFilterWhere(['like', 'tbl_complain_escalation.escalation_name', $this->complain_escalation_code]);
        ;

        return $dataProvider;
    }

}
