<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcs;
use yii\db\Expression;

/**
 * TblDcsSearch represents the model behind the search form about `app\modules\organisation\models\TblDcs`.
 */
class TblDcsSearch extends TblDcs {

    public $federation_code, $customer_type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_code', 'address', 'upi_no', 'destination_type', 'bank_account_no', 'contact_person', 'created_at', 'dcs_code_ex', 'dcs_name', 'dcs_short_name', 'milk_type_code', 'destination_code', 'effective_date', 'email', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'pincode', 'registration_code', 'registration_date', 'service_tax', 'tin_no', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'district_code', 'hamlet_code', 'route_code', 'state_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'federation_code', 'organisation_type_code', 'scheme_type_code', 'is_registerd', 'valid_from', 'dpu_type', 'customer_type', 'is_chiller', 'machine_owned'], 'safe'],
                [['allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code', 'machine_owned'], 'integer'],
                [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'], 'required', 'on' => ['dpuPassword']],
                [['dcs_code_ex', 'ref_code', 'aadhaar_no', 'ts_code_m', 'ts_code_e'], 'safe'],
                [['customer_type', 'union_code', 'plant_code', 'mcc_plant_code', 'mcc_code', 'bmc_code', 'type_of_dcs'], 'safe'],
                [['customer_type', 'union_code', 'plant_code', 'mcc_plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['deleteMapRoute']]
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

        $query->joinWith(['stateCode', 'districtCode', 'defaultMobileNo', 'mainBankDetails']);

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
            'tbl_dcs.dpu_type' => $this->dpu_type,
            'tbl_dcs.is_chiller' => $this->is_chiller,
            'tbl_dcs.x_col2' => $this->x_col2,
            'tbl_dcs.machine_owned' => $this->machine_owned,
        ]);

        if (!empty($this->registration_date))
            $query->andFilterWhere(['like', 'tbl_dcs.registration_date', date('Y-m-d', strtotime($this->registration_date))]);

        $query->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs.address', $this->address])
                ->andFilterWhere(['like', 'tbl_bank_details.bank_account_no', $this->bank_account_no])
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
                ->andFilterWhere(['like', 'tbl_contact_details.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'aadhaar_no', Yii::$app->general->encryptData($this->aadhaar_no)])
                ->andFilterWhere(['like', 'tbl_dcs.ts_code_m', $this->ts_code_m])
                ->andFilterWhere(['like', 'tbl_dcs.ts_code_e', $this->ts_code_e])
                ->andFilterWhere(['like', 'tbl_dcs.type_of_dcs', $this->type_of_dcs]);

        return $dataProvider;
    }

    public function dpupasssearch($params) {
        $query = TblDcs::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['dcs_name' => SORT_ASC]],
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        $query->andWhere(['tbl_dcs.bmc_code' => $this->f_bmc_code]);
        $query->andWhere('tbl_dcs.bmc_code is not null');
        $query->andFilterWhere(['tbl_dcs.dcs_code' => $this->dcs_code]);

        return $dataProvider;
    }

    public function deleteroutemapsearch($params) {
        $this->load($params);
        $query = Tbldcs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['routeSourceMapping'], true, 'INNER JOIN');

        $query->andFilterWhere([
            'tbl_dcs.plant_code' => $this->plant_code,
            'tbl_dcs.mcc_plant_code' => $this->mcc_plant_code,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (empty($this->bmc_code)) {
            $query->where('0=1');
        } else {
            $query->andWhere(['tbl_dcs.bmc_code' => $this->bmc_code]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'tbl_dcs.route_code', $this->route_code]);

        return $dataProvider;
    }

}
