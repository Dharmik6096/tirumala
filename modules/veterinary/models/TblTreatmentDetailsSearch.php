<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblTreatmentDetails;

/**
 * TblTreatmentDetailsSearch represents the model behind the search form about `app\modules\veterinary\models\TblTreatmentDetails`.
 */
class TblTreatmentDetailsSearch extends TblTreatmentDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id', 'animal_treatment_request_id', 'diagnosis_detail_id', 'medicine_id', 'uom', 'originating_type'], 'integer'],
            [['ref_code', 'batch_no', 'route', 'remarks', 'tran_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['qty'], 'number'],
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
        $query = TblTreatmentDetails::find();

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
            'treatment_id' => $this->treatment_id,
            'animal_treatment_request_id' => $this->animal_treatment_request_id,
            'diagnosis_detail_id' => $this->diagnosis_detail_id,
            'medicine_id' => $this->medicine_id,
            'qty' => $this->qty,
            'uom' => $this->uom,
            'tran_datetime' => $this->tran_datetime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'ref_code', $this->ref_code])
            ->andFilterWhere(['like', 'batch_no', $this->batch_no])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
