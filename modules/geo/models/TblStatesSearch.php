<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblStates;

/**
 * TblStatesSearch represents the model behind the search form about `app\models\TblStates`.
 */
class TblStatesSearch extends TblStates {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['state_code', 'created_at', 'state_name','updated_at', 'created_by', 'updated_by'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblStates::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['state_name' => SORT_ASC]],
        ]);


        $query->andWhere(['like', 'tbl_states.state_code', Yii::$app->session->get('States')]);

        if (isset($_GET['TblStatesSearch']) && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->load($params);

        $query->andFilterWhere([
            'tbl_states.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_states.state_code', $this->state_code])
                ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_name]);
               // ->andFilterWhere(['like', 'tbl_states_local.local_name', $this->local_name]);

        return $dataProvider;
    }

}
