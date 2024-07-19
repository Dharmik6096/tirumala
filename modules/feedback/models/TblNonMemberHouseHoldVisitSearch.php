<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblNonMemberHouseHoldVisit;

/**
 * TblNonMemberHouseHoldVisitSearch represents the model behind the search form about `app\modules\feedback\models\TblNonMemberHouseHoldVisit`.
 */
class TblNonMemberHouseHoldVisitSearch extends TblNonMemberHouseHoldVisit
{
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_hold_visit_id','house_hold_visit_code','surveyer_code','visit_date','mcc_plant_code','bmc_code','dcs_code','name','address_line','pincode','mobile_no','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','cow_milk_volume','buff_milk_volume','total_milk_volume','own_milk_consumption','balance_milk','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
            [['from_date', 'to_date'], 'safe'],

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
        $query = TblNonMemberHouseHoldVisit::find();

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
        $query->joinWith(['mccPlantCode', 'bmcCode', 'dcsCode', 'surveyerCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_plant', 'tbl_mcc_plant', 'tbl_bmc', 'tbl_dcs');

        if (!empty($this->visit_date)) {
            $visit_date = date('Y-m-d', strtotime($this->visit_date));
            $query->andFilterWhere(['cast(tbl_non_member_house_hold_visit.visit_date as date)' => $visit_date]);
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_non_member_house_hold_visit.visit_date as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_non_member_house_hold_visit.visit_date as date)', $to_date]);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_non_member_house_hold_visit.house_hold_visit_id' => $this->house_hold_visit_id,
            'tbl_non_member_house_hold_visit.milch_animal_cow_cnt' => $this->milch_animal_cow_cnt,
            'tbl_non_member_house_hold_visit.milch_animal_buff_cnt' => $this->milch_animal_buff_cnt,
            'tbl_non_member_house_hold_visit.milch_animal_country_cow_cnt' => $this->milch_animal_country_cow_cnt,
            'tbl_non_member_house_hold_visit.cow_milk_volume' => $this->cow_milk_volume,
            'tbl_non_member_house_hold_visit.buff_milk_volume' => $this->buff_milk_volume,
            'tbl_non_member_house_hold_visit.total_milk_volume' => $this->total_milk_volume,
            'tbl_non_member_house_hold_visit.own_milk_consumption' => $this->own_milk_consumption,
            'tbl_non_member_house_hold_visit.balance_milk' => $this->balance_milk,
        ]);

        $query->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.house_hold_visit_code', $this->house_hold_visit_code])
            ->andFilterWhere(['like', 'user.name', $this->surveyer_code])
            ->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.name', $this->name])
            ->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.address_line', $this->address_line])
            ->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.pincode', $this->pincode])
            ->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'tbl_non_member_house_hold_visit.remarks', $this->remarks]);

        return $dataProvider;
    }
}
