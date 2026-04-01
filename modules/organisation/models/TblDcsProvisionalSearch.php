<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsProvisional;
use yii\db\Expression;
use app\modules\general\models\TblProcessApproval;

/**
 * TblDcsProvisionalSearch represents the model behind the search form about `app\modules\organisation\models\TblDcsProvisional`.
 */
class TblDcsProvisionalSearch extends TblDcsProvisional {

    public $federation_code, $customer_type, $from_date, $to_date, $f_union_code, $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'address', 'upi_no', 'destination_type', 'bank_account_no', 'contact_person', 'created_at', 'dcs_code_ex', 'dcs_name', 'dcs_short_name', 'milk_type_code', 'destination_code', 'effective_date', 'email', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'pincode', 'registration_code', 'registration_date', 'service_tax', 'tin_no', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'district_code', 'hamlet_code', 'route_code', 'state_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'federation_code', 'organisation_type_code', 'scheme_type_code', 'is_registerd', 'valid_from', 'dpu_type', 'customer_type', 'is_chiller', 'status', 'dcs_status', 'supervisor_employee_id', 'supervisor_employee_name', 'provisional_from', 'is_approved', 'approved_at', 'approved_by', 'is_bank_verify', 'is_aadhar_verify'], 'safe'],
            [['allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code'], 'integer'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'], 'required', 'on' => ['dpuPassword']],
            [['dcs_code_ex', 'ref_code', 'aadhaar_no', 'ts_code_m', 'ts_code_e'], 'safe'],
            [['customer_type', 'union_code', 'plant_code', 'mcc_plant_code', 'mcc_code', 'bmc_code'], 'safe'],
            [['customer_type', 'union_code', 'plant_code', 'mcc_plant_code', 'mcc_code', 'bmc_code'], 'required', 'on' => ['deleteMapRoute']],
            [['from_date', 'to_date'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
            [['latitude', 'longitude'], 'safe'],
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
    public function search($params, $pending_approval = false) {
        $query = TblDcsProvisional::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['dcs_name' => SORT_ASC]],
        ]);

        $query->joinWith(['stateCode', 'districtCode', 'defaultMobileNo']);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($pending_approval) {
            $approvalModel = new TblProcessApproval();
            $subQuery = $approvalModel->getApproveLavel('society');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_dcs_provisional.dcs_provisional_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['tbl_dcs_provisional.*', 'ap.process_approval_code as process_approval_code']);
            $this->status = ['Register', 'Inprogress'];
            $query->where(['tbl_dcs_provisional.status' => $this->status, 'tbl_dcs_provisional.is_active' => 1]);
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_provisional', 'tbl_dcs_provisional', 'tbl_dcs_provisional', 'tbl_dcs_provisional');

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_dcs_provisional.allow_multi_family_member' => $this->allow_multi_family_member,
            'tbl_dcs_provisional.destination_type' => $this->destination_type,
            'tbl_dcs_provisional.effective_date' => $this->effective_date,
            'tbl_dcs_provisional.is_active' => $this->is_active,
            'tbl_dcs_provisional.is_bmc' => $this->is_bmc,
            'tbl_dcs_provisional.dcs_type_code' => $this->dcs_type_code,
            'tbl_dcs_provisional.milk_type_code' => $this->milk_type_code,
            'tbl_dcs_provisional.dpu_type' => $this->dpu_type,
            'tbl_dcs_provisional.is_chiller' => $this->is_chiller,
            'tbl_dcs_provisional.x_col2' => $this->x_col2,
            'tbl_dcs_provisional.status' => $this->status,
            'tbl_dcs_provisional.data_post_status' => $this->data_post_status,
        ]);

        if (!empty($this->registration_date))
            $query->andFilterWhere(['like', 'tbl_dcs_provisional.registration_date', date('Y-m-d', strtotime($this->registration_date))]);

        $this->from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_dcs_provisional.created_at as date)', $this->from_date]);

        $this->to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_dcs_provisional.created_at as date)', $this->to_date]);

        $query->andFilterWhere(['like', 'tbl_dcs_provisional.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.address', $this->address])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.dcs_code_ex', $this->dcs_code_ex])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.dcs_short_name', $this->dcs_short_name])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.email', $this->email])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.phone_no', $this->phone_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.pincode', $this->pincode])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.registration_code', $this->registration_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.service_tax', $this->service_tax])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.tin_no', $this->tin_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'tbl_districts.district_code', $this->district_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.route_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_states.state_name', $this->state_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.aadhaar_no', $this->aadhaar_no])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.ts_code_m', $this->ts_code_m])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.ts_code_e', $this->ts_code_e])
                ->andFilterWhere(['like', 'tbl_dcs_provisional.provisional_from', $this->provisional_from]);

        return $dataProvider;
    }

    public function dcsApproveSearch($params) {
        $query = TblDcsProvisional::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        $query->joinWith(['schemeId']);
        $levelQuery = TblProcessApproval::find()
                ->select(['process_code', 'MIN(level_priority) AS level_priority'])
                ->where(['status' => 0])
                ->groupBy('process_code');

        $subQuery = TblProcessApproval::find()
                ->select(['tbl_process_approval.process_code', 'tbl_process_approval.process_approval_code'])
                ->innerJoin(['lq' => $levelQuery], 'tbl_process_approval.process_code = lq.process_code AND tbl_process_approval.level_priority = lq.level_priority')
                ->where(['tbl_process_approval.status' => 0])
                ->andWhere(['tbl_process_approval.user_code' => \Yii::$app->user->identity->user_code]);

        $query->innerJoin(['ap' => $subQuery], 'tbl_dcs_provisional.dcs_provisional_code = ap.process_code');
        $query->addSelect(['tbl_dcs_provisional.*', 'ap.process_approval_code as process_approval_code']);
        $status = ['Register', 'Inprogress'];
        $query->where(['tbl_dcs_provisional.status' => $status]);

        return $dataProvider;
    }

}
