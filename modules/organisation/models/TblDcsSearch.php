<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcs;

/**
 * TblDcsSearch represents the model behind the search form about `app\modules\organisation\models\TblDcs`.
 */
class TblDcsSearch extends TblDcs {

    public $federation_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'address', 'upi_no', 'destination_type', 'bank_account_no', 'contact_person', 'created_at', 'dcs_code_ex', 'dcs_name', 'dcs_short_name', 'milk_type_code', 'destination_code', 'effective_date', 'email', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'pincode', 'registration_code', 'registration_date', 'service_tax', 'tin_no', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'district_code', 'hamlet_code', 'route_code', 'state_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'federation_code', 'organisation_type_code', 'scheme_type_code', 'is_registerd', 'valid_from'], 'safe'],
            [['allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code'], 'integer'],
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
        $query = TblDcs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['dcs_name' => SORT_ASC]],
        ]);

        $query->joinWith(['stateCode', 'districtCode', 'defaultMobileNo']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_dcs.allow_multi_family_member' => $this->allow_multi_family_member,
            'tbl_dcs.destination_type' => $this->destination_type,
            'tbl_dcs.effective_date' => $this->effective_date,
            'tbl_dcs.is_active' => $this->is_active,
            'tbl_dcs.is_bmc' => $this->is_bmc,
            'tbl_dcs.dcs_type_code' => $this->dcs_type_code,
            'tbl_dcs.milk_type_code' => $this->milk_type_code,
        ]);

        if (!empty($this->registration_date))
            $query->andFilterWhere(['like', 'tbl_dcs.registration_date', date('Y-m-d', strtotime($this->registration_date))]);

        $query->andFilterWhere(['like', 'tbl_dcs.address', $this->address])
                ->andFilterWhere(['like', 'tbl_dcs.bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'tbl_dcs.contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', $this->dcs_code_ex])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_short_name', $this->dcs_short_name])
                ->andFilterWhere(['like', 'tbl_dcs.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_dcs.email', $this->email])
                ->andFilterWhere(['like', 'tbl_dcs.ifsc', $this->ifsc])
//                ->andFilterWhere(['like', 'tbl_dcs.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_dcs.pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'tbl_dcs.phone_no', $this->phone_no])
                ->andFilterWhere(['like', 'tbl_dcs.pincode', $this->pincode])
                ->andFilterWhere(['like', 'tbl_dcs.registration_code', $this->registration_code])
                ->andFilterWhere(['like', 'tbl_dcs.service_tax', $this->service_tax])
                ->andFilterWhere(['like', 'tbl_dcs.tin_no', $this->tin_no])
                ->andFilterWhere(['like', 'tbl_dcs.bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'tbl_dcs.branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'tbl_districts.district_code', $this->district_code])
                ->andFilterWhere(['like', 'tbl_dcs.hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'tbl_dcs.route_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_code])
                ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_dcs.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_contact_details.mobile_no', $this->mobile_no]);

        return $dataProvider;
    }

}
