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
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_treatment_request_id','case_no','dcs_code','member_code','member_name','member_type','mobile_number','address','case_type_id','member_animal_tag_id','disease_id','animal_type_id','breed_id','gender_id','year','month','tran_datetime','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'], 'safe'],
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

        $query->joinWith(['dcsCode', 'animalTypeId', 'breedId', 'genderId', 'caseTypeId', 'memberAnimalTagId', 'diseaseId']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs', 'tbl_dcs', 'tbl_dcs', 'tbl_dcs');

        if(!empty($this->from_date)){
            $query->andWhere(['>=', 'tbl_animal_treatment_request.tran_datetime', date('Y-m-d', strtotime($this->from_date))]);
        }
        if(!empty($this->to_date)){
            $query->andWhere(['<=', 'tbl_animal_treatment_request.tran_datetime', date('Y-m-d', strtotime($this->to_date))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_animal_treatment_request.animal_treatment_request_id' => $this->animal_treatment_request_id,
            'tbl_animal_treatment_request.year' => $this->year,
            'tbl_animal_treatment_request.month' => $this->month,
            'tbl_animal_treatment_request.member_code' => $this->member_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_animal_treatment_request.case_no', $this->case_no])
            ->andFilterWhere(['like', 'tbl_animal_treatment_request.member_name', $this->member_name])
            ->andFilterWhere(['like', 'tbl_animal_treatment_request.member_type', $this->member_type])
            ->andFilterWhere(['like', 'tbl_animal_treatment_request.mobile_number', $this->mobile_number])
            ->andFilterWhere(['like', 'tbl_animal_treatment_request.address', $this->address])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_member_animal_type.animal_type_name', $this->animal_type_id])
            ->andFilterWhere(['like', 'tbl_breed_master.breed_name', $this->breed_id])
            ->andFilterWhere(['like', 'tbl_gender.gender', $this->gender_id])
            ->andFilterWhere(['like', 'tbl_case_type.case_type_name', $this->case_type_id])
            ->andFilterWhere(['like', 'tbl_member_animal_tag_details.tag_no', $this->member_animal_tag_id])
            ->andFilterWhere(['like', 'tbl_disease_master.disease_name', $this->disease_id]);

        return $dataProvider;
    }
}
