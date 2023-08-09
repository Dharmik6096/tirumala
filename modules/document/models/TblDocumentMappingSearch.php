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
        $query->joinWith(['docId']);

        // grid filtering conditions
        $query->andFilterWhere([
            'is_mandate' => $this->is_mandate,
            'master_type' => $this->master_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_document_master_info.doc_name', $this->doc_id]);

        return $dataProvider;
    }

}
