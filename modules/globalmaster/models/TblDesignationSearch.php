<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblDesignation;

/**
 * TblDesignationSearch represents the model behind the search form about `app\models\TblDesignation`.
 */
class TblDesignationSearch extends TblDesignation {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_active'], 'integer'],
            [['designation_code', 'designation_type', 'created_at', 'deleted_at', 'designation_name', 'updated_at', 'created_by', 'deleted_by', 'updated_by', 'local_name'], 'safe'],
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
        $query = TblDesignation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['designation_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['designation_name' => SORT_ASC]],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_designation.is_active' => $this->is_active,
            'tbl_designation.designation_type' => $this->designation_type,
        ]);

        $query->andFilterWhere(['like', 'designation_name', $this->designation_name])
                ->andFilterWhere(['like', 'tbl_designation.designation_code', $this->designation_code]);
        ;
        return $dataProvider;
    }

}
