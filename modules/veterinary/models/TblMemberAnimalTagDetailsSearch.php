<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblMemberAnimalTagDetails;

/**
 * TblMemberAnimalTagDetailsSearch represents the model behind the search form about `app\modules\veterinary\models\TblMemberAnimalTagDetails`.
 */
class TblMemberAnimalTagDetailsSearch extends TblMemberAnimalTagDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_animal_tag_id','dcs_code','member_code','mobile_no','email','tag_no','animal_type_id','gender_id','breed_id','year','month','no_of_calving','last_date_of_calving','pregnancy_status','pregnancy_month','pregnancy_month_on_date','milking_status','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
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
        $query = TblMemberAnimalTagDetails::find();

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
        $query->joinWith(['dcsCode', 'animalTypeId', 'breedId', 'genderId']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs', 'tbl_dcs', 'tbl_dcs', 'tbl_dcs');
        if(!empty($this->last_date_of_calving)){
            $last_date_of_calving = date('Y-m-d', strtotime($this->last_date_of_calving));
            $query->andWhere(['tbl_member_animal_tag_details.last_date_of_calving' => $last_date_of_calving]);
        }
        if(!empty($this->pregnancy_month_on_date)){
            $pregnancy_month_on_date = date('Y-m-d', strtotime($this->pregnancy_month_on_date));
            $query->andWhere(['cast(tbl_member_animal_tag_details.pregnancy_month_on_date as date)' => $pregnancy_month_on_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_member_animal_tag_details.member_animal_tag_id' => $this->member_animal_tag_id,
            'tbl_member_animal_tag_details.year' => $this->year,
            'tbl_member_animal_tag_details.month' => $this->month,
            'tbl_member_animal_tag_details.no_of_calving' => $this->no_of_calving,
            'tbl_member_animal_tag_details.pregnancy_status' => $this->pregnancy_status,
            'tbl_member_animal_tag_details.pregnancy_month' => $this->pregnancy_month,
            'tbl_member_animal_tag_details.milking_status' => $this->milking_status,
            'tbl_member_animal_tag_details.member_code' =>  $this->member_code
        ]);

        $query->andFilterWhere(['like', 'tbl_member_animal_tag_details.mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'tbl_member_animal_tag_details.email', $this->email])
            ->andFilterWhere(['like', 'tbl_member_animal_tag_details.tag_no', $this->tag_no])
            ->andFilterWhere(['like', 'tbl_member_animal_type.animal_type_name', $this->animal_type_id])
            ->andFilterWhere(['like', 'tbl_breed_master.breed_name', $this->breed_id])
            ->andFilterWhere(['like', 'tbl_gender.gender', $this->gender_id]);

        return $dataProvider;
    }
}
