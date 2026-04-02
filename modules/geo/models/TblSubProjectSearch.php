<?php

namespace app\modules\geo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblSubProject;

/**
 * TblSubProjectSearch represents the model behind the search form about `app\modules\geo\models\TblSubProject`.
 */
class TblSubProjectSearch extends TblSubProject {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['project_code', 'sub_project_name', 'description', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblSubProject::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith('projectCode');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_sub_project.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'sub_project_name', $this->sub_project_name])
                ->andFilterWhere(['like', 'tbl_project.project_name', $this->project_code]);

        return $dataProvider;
    }

}
