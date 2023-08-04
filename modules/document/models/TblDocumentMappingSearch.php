<?php

namespace app\modules\document\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\document\models\TblDocumentMapping;

/**
 * TblDocumentMappingSearch represents the model behind the search form about `app\modules\document\models\TblDocumentMapping`.
 */
class TblDocumentMappingSearch extends TblDocumentMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mapping_id', 'doc_id', 'is_mandate', 'originating_type', 'master_type', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblDocumentMapping::find();

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
            'mapping_id' => $this->mapping_id,
            'doc_id' => $this->doc_id,
            'is_mandate' => $this->is_mandate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
            'master_type' => $this->master_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
