<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;

/**
 * TblSchemeDocumentMasterSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeDocumentMaster`.
 */
class TblSchemeDocumentMasterSearch extends TblSchemeDocumentMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['doc_id', 'is_active', 'originating_type'], 'integer'],
                [['doc_id', 'is_active', 'originating_type', 'doc_group', 'doc_name', 'doc_ext', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblSchemeDocumentMaster::find();

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
            'doc_id' => $this->doc_id,
            'tbl_scheme_document_master.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'doc_group', $this->doc_group])
                ->andFilterWhere(['like', 'doc_name', $this->doc_name])
                ->andFilterWhere(['like', 'doc_ext', $this->doc_ext]);

        return $dataProvider;
    }

    public function mappingsearch($params) {
        $query = TblSchemeDocumentMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere([
            'is_active' => $this->is_active,
        ]);


        return $dataProvider;
    }

}
