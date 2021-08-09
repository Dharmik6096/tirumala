<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\TblAction;

/**
 * TblActionSearch represents the model behind the search form about `app\modules\installation\models\TblAction`.
 */
class TblActionSearch extends TblAction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['action_code', 'menu_level', 'parent_code', 'action_type'], 'integer'],
            [['action_name', 'description', 'menu_level','parent_code'], 'safe'],
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
        $query = TblAction::find();

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
            'action_code' => $this->action_code,
            
        ]);

        $query->andFilterWhere(['like', 'action_name', $this->action_name])
                ->andFilterWhere(['like', 'menu_level', $this->menu_level])
                ->andFilterWhere(['like', 'parent_code', $this->parent_code])
                ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

}
