<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblDiagnosisDetails;

/**
 * TblDiagnosisDetailsSearch represents the model behind the search form about `app\modules\veterinary\models\TblDiagnosisDetails`.
 */
class TblDiagnosisDetailsSearch extends TblDiagnosisDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disease_id','disease_name','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','is_active'], 'safe'],
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
        $query = TblDiagnosisDetails::find();

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
            'diagnosis_detail_id' => $this->diagnosis_detail_id,
            'animal_treatment_request_id' => $this->animal_treatment_request_id,
            'milking_status' => $this->milking_status,
            'milk_production' => $this->milk_production,
            'symptom_id' => $this->symptom_id,
            'case_fee' => $this->case_fee,
            'tran_datetime' => $this->tran_datetime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'disease_id', $this->disease_id])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'lat_long', $this->lat_long])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'gateway', $this->gateway])
            ->andFilterWhere(['like', 'payment_mode', $this->payment_mode])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
