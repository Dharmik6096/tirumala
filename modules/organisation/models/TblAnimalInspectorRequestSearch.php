<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblAnimalInspectorRequest;

/**
 * TblAnimalInspectorRequestSearch represents the model behind the search form about `app\modules\organisation\models\TblAnimalInspectorRequest`.
 */
class TblAnimalInspectorRequestSearch extends TblAnimalInspectorRequest {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['animal_inspector_code', 'originating_type', 'animal_inspector_request_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'user_type', 'member_code', 'member_name', 'mobile_no', 'address', 'ai_request_for', 'expected_visit_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblAnimalInspectorRequest::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['animalInspectorCode', 'memberCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_animal_inspector_request', 'tbl_animal_inspector_request', 'tbl_animal_inspector_request');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->expected_visit_date))
            $query->andFilterWhere(['like', 'expected_visit_date', date('Y-m-d', strtotime($this->expected_visit_date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        if (!empty($this->member_name)) {
            $query->andWhere(['or', ['like', 'tbl_animal_inspector_request.member_name', $this->member_name], ['like', 'tbl_member.member_name', $this->member_name]]);
        }

        $query->andFilterWhere(['like', 'animal_inspector_request_code', $this->animal_inspector_request_code])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.user_type', $this->user_type])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.member_code', $this->member_code])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.address', $this->address])
                ->andFilterWhere(['like', 'tbl_animal_inspector.ai_name', $this->animal_inspector_code])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.ai_request_for', $this->ai_request_for])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.created_by', $this->created_by])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'tbl_animal_inspector_request.originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
