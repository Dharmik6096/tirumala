<?php

namespace app\modules\document\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\document\models\TblAttachment;

/**
 * TblAttachmentSearch represents the model behind the search form about `app\modules\document\models\TblAttachment`.
 */
class TblAttachmentSearch extends TblAttachment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['attachment_code', 'doc_id', 'originating_type', 'module_code', 'module_name', 'attachment_type', 'remarks', 'file_name', 'attachment', 'lat_long', 'parent_code', 'device_id', 'thumbnail', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblAttachment::find();

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
            'attachment_code' => $this->attachment_code,
            'doc_id' => $this->doc_id,
            'created_at' => $this->created_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'module_code', $this->module_code])
                ->andFilterWhere(['like', 'module_name', $this->module_name])
                ->andFilterWhere(['like', 'attachment_type', $this->attachment_type])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'attachment', $this->attachment])
                ->andFilterWhere(['like', 'lat_long', $this->lat_long])
                ->andFilterWhere(['like', 'parent_code', $this->parent_code])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
                ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
