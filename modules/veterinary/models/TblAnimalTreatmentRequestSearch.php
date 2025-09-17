<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblAnimalTreatmentRequest;

/**
 * TblAnimalTreatmentRequestSearch represents the model behind the search form about `app\modules\veterinary\models\TblAnimalTreatmentRequest`.
 */
class TblAnimalTreatmentRequestSearch extends TblAnimalTreatmentRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_treatment_request_id', 'case_type_id', 'member_animal_tag_id', 'disease_id', 'animal_type_id', 'breed_id', 'gender_id', 'year', 'month', 'originating_type'], 'integer'],
            [['case_no', 'dcs_code', 'member_code', 'member_name', 'member_type', 'mobile_number', 'address', 'tran_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblAnimalTreatmentRequest::find();

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
            'animal_treatment_request_id' => $this->animal_treatment_request_id,
            'case_type_id' => $this->case_type_id,
            'member_animal_tag_id' => $this->member_animal_tag_id,
            'disease_id' => $this->disease_id,
            'animal_type_id' => $this->animal_type_id,
            'breed_id' => $this->breed_id,
            'gender_id' => $this->gender_id,
            'year' => $this->year,
            'month' => $this->month,
            'tran_datetime' => $this->tran_datetime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'case_no', $this->case_no])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'member_type', $this->member_type])
            ->andFilterWhere(['like', 'mobile_number', $this->mobile_number])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
