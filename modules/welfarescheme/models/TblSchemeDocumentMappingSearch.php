<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeDocumentMapping;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;

/**
 * TblSchemeDocumentMappingSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeDocumentMapping`.
 */
class TblSchemeDocumentMappingSearch extends TblSchemeDocumentMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mapping_id', 'scheme_id', 'doc_id', 'is_mandate', 'originating_type'], 'integer'],
                [['mapping_id', 'scheme_id', 'doc_id', 'is_mandate', 'originating_type', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblSchemeDocumentMapping::find();

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
            'scheme_id' => $this->scheme_id,
            'doc_id' => $this->doc_id,
            'is_mandate' => $this->is_mandate,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
