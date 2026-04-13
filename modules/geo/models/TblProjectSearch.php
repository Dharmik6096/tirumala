<?php

namespace app\modules\geo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblProject;

/**
 * TblProjectSearch represents the model behind the search form about `app\modules\geo\models\TblProject`.
 */
class TblProjectSearch extends TblProject {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['project_code', 'is_active', 'originating_type'], 'integer'],
            [['project_name', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblProject::find();

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
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'project_name', $this->project_name])
                ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

}
