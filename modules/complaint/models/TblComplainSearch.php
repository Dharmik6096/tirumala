<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplain;

/**
 * TblComplainSearch represents the model behind the search form about `app\modules\complaint\models\TblComplain`.
 */
class TblComplainSearch extends TblComplain {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['complain_code', 'complain_type_code', 'complain_problem_code', 'physical_damage', 'spare_required', 'affects_data', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'location_type', 'complain_for', 'complain_datetime', 'complain_assignment_datetime', 'asset_code', 'serial_number', 'new_serial_no', 'contact_person', 'mobile_no', 'complain_status', 'complain_status_datetime', 'user_code', 'lat_long', 'location_details', 'remarks', 'entry_type', 'resolved_status', 'resolved_datetime', 'resolved_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_date', 'to_date'], 'safe'],
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
    public function search($params, $complainInfo = false) {
        $query = TblComplain::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if($complainInfo){
            $query->innerJoin('tbl_allow_manual_collection_range amcr', 'tbl_complain.complain_codes = amcr.complain_code');
            $query->andWhere(['amcr.dcs_code' => $this->dcs_code, 'amcr.table_name'=>'tbl_milk_collection', 'amcr.application_type'=>'DCS']);
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_complain', 'tbl_complain', 'tbl_complain', 'tbl_complain');

        if (!empty($this->complain_datetime)) {
            $query->andFilterWhere(['like', 'cast(tbl_complain.complain_datetime as date)', date('Y-m-d', strtotime($this->complain_datetime))]);
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'complain_datetime', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'complain_datetime', $to_date]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'complain_code' => $this->complain_code,
            'complain_type_code' => $this->complain_type_code,
            'complain_assignment_datetime' => $this->complain_assignment_datetime,
            'complain_problem_code' => $this->complain_problem_code,
            'complain_status_datetime' => $this->complain_status_datetime,
            'physical_damage' => $this->physical_damage,
            'spare_required' => $this->spare_required,
            'affects_data' => $this->affects_data,
            'resolved_datetime' => $this->resolved_datetime,
        ]);

        $query->andFilterWhere(['like', 'location_type', $this->location_type])
                ->andFilterWhere(['like', 'complain_for', $this->complain_for])
                ->andFilterWhere(['like', 'asset_code', $this->asset_code])
                ->andFilterWhere(['like', 'serial_number', $this->serial_number])
                ->andFilterWhere(['like', 'new_serial_no', $this->new_serial_no])
                ->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'complain_status', $this->complain_status])
                ->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'lat_long', $this->lat_long])
                ->andFilterWhere(['like', 'location_details', $this->location_details])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'entry_type', $this->entry_type])
                ->andFilterWhere(['like', 'resolved_status', $this->resolved_status])
                ->andFilterWhere(['like', 'resolved_remarks', $this->resolved_remarks]);

        return $dataProvider;
    }

}
