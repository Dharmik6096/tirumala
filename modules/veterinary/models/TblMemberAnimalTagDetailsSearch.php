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

        // grid filtering conditions
        $query->andFilterWhere([
            'member_animal_tag_id' => $this->member_animal_tag_id,
            'animal_type_id' => $this->animal_type_id,
            'gender_id' => $this->gender_id,
            'breed_id' => $this->breed_id,
            'year' => $this->year,
            'month' => $this->month,
            'no_of_calving' => $this->no_of_calving,
            'last_date_of_calving' => $this->last_date_of_calving,
            'pregnancy_status' => $this->pregnancy_status,
            'pregnancy_month' => $this->pregnancy_month,
            'pregnancy_month_on_date' => $this->pregnancy_month_on_date,
            'milking_status' => $this->milking_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'tag_no', $this->tag_no])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
