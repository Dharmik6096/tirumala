<?php

namespace app\modules\organisation\models;

use app\modules\dcsoperation\models\TblMemberProvisional;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\general\models\TblProcessApproval;
use yii\data\ArrayDataProvider;

/**
 * TblCustomerMasterProvisionalSearch represents the model behind the search form about `app\modules\organisation\models\TblCustomerMasterProvisional`.
 */
class TblCustomerMasterProvisionalSearch extends TblCustomerMasterProvisional {

    public $from_date, $to_date, $table_name, $report_type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'customer_code', 'customer_code_ex', 'customer_name', 'customer_type', 'sap_code', 'refference_code', 'address', 'local_name', 'local_address', 'gst_no', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'rate_chart_code', 'billing_payment_cycle', 'over_head', 'ccenter_code', 'ref_code', 'old_bmc_code', 'old_mcc_plant_code', 'old_route_code', 'vendor_code', 'data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'aadhaar_no', 'ts_code_m', 'ts_code_e', 'sap_vendor_code', 'customer_category', 'distance_from_mcc', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'contact_person', 'email', 'mobile_no', 'local_contact_person', 'department', 'firstname', 'lastname', 'surname', 'local_firstname', 'local_lastname', 'local_surname', 'status', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'morning_kms', 'evening_kms', 'customer_provisional_code', 'auto_code', 'data_post_status', 'animal_type_code', 'originating_type'], 'safe'],
                [['latitude', 'longitude', 'gender_code', 'pincode', 'pan_no', 'customer_status', 'supervisor_employee_id', 'supervisor_employee_name', 'from_date', 'to_date', 'table_name', 'report_type', 'is_aadhar_verify', 'is_bank_verify', 'provisional_from', 'is_approved', 'approved_at', 'approved_by'], 'safe'],
                [['from_date', 'to_date'], 'required', 'on' => 'ApprovedAttachmentDetails'],
                [['from_date',], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
                }, 'on' => 'ApprovedAttachmentDetails'],
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
        $query = TblCustomerMasterProvisional::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($pending_approval) {
            $approvalModel = new TblProcessApproval();
            $subQuery = $approvalModel->getApproveLavel('tbl_customer_master_provisional');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_customer_master_provisional.customer_provisional_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['tbl_customer_master_provisional.*', 'ap.process_approval_code as process_approval_code']);
            $this->status = ['Register', 'Inprogress'];
            $query->where(['tbl_customer_master_provisional.status' => $this->status]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_customer_master_provisional', 'tbl_customer_master_provisional', 'tbl_customer_master_provisional');
        if (!$pending_approval) {
            $query->andFilterWhere(['tbl_customer_master_provisional.status' => $this->status]);
        }

        $this->from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_customer_master_provisional.created_at as date)', $this->from_date]);

        $this->to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_customer_master_provisional.created_at as date)', $this->to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'customer_provisional_code' => $this->customer_provisional_code,
            'morning_kms' => $this->morning_kms,
            'evening_kms' => $this->evening_kms,
            'auto_code' => $this->auto_code,
            'data_post_status' => $this->data_post_status,
            'picked_datetime' => $this->picked_datetime,
            'response_datetime' => $this->response_datetime,
            'animal_type_code' => $this->animal_type_code,
            'route_code' => $this->route_code,
        ]);

        $query->andFilterWhere(['like', 'customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'customer_code_ex', $this->customer_code_ex])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'sap_code', $this->sap_code])
                ->andFilterWhere(['like', 'refference_code', $this->refference_code])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'local_name', $this->local_name])
                ->andFilterWhere(['like', 'local_address', $this->local_address])
                ->andFilterWhere(['like', 'gst_no', $this->gst_no])
                ->andFilterWhere(['like', 'state_code', $this->state_code])
                ->andFilterWhere(['like', 'district_code', $this->district_code])
                ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'village_code', $this->village_code])
                ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'rate_chart_code', $this->rate_chart_code])
                ->andFilterWhere(['like', 'billing_payment_cycle', $this->billing_payment_cycle])
                ->andFilterWhere(['like', 'over_head', $this->over_head])
                ->andFilterWhere(['like', 'ccenter_code', $this->ccenter_code])
                ->andFilterWhere(['like', 'ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'old_bmc_code', $this->old_bmc_code])
                ->andFilterWhere(['like', 'old_mcc_plant_code', $this->old_mcc_plant_code])
                ->andFilterWhere(['like', 'old_route_code', $this->old_route_code])
                ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'data_post_id', $this->data_post_id])
                ->andFilterWhere(['like', 'resp_status', $this->resp_status])
                ->andFilterWhere(['like', 'resp_desc', $this->resp_desc])
                ->andFilterWhere(['like', 'aadhaar_no', $this->aadhaar_no])
                ->andFilterWhere(['like', 'ts_code_m', $this->ts_code_m])
                ->andFilterWhere(['like', 'ts_code_e', $this->ts_code_e])
                ->andFilterWhere(['like', 'sap_vendor_code', $this->sap_vendor_code])
                ->andFilterWhere(['like', 'customer_category', $this->customer_category])
                ->andFilterWhere(['like', 'distance_from_mcc', $this->distance_from_mcc])
                ->andFilterWhere(['like', 'bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name])
                ->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'local_contact_person', $this->local_contact_person])
                ->andFilterWhere(['like', 'department', $this->department])
                ->andFilterWhere(['like', 'firstname', $this->firstname])
                ->andFilterWhere(['like', 'lastname', $this->lastname])
                ->andFilterWhere(['like', 'surname', $this->surname])
                ->andFilterWhere(['like', 'local_firstname', $this->local_firstname])
                ->andFilterWhere(['like', 'local_lastname', $this->local_lastname])
                ->andFilterWhere(['like', 'local_surname', $this->local_surname])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'provisional_from', $this->provisional_from])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5])
                ->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }

    public function approvedAttachmentDetailsSearch($params) {
        $this->load($params);
        $fromDate = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : null;
        $toDate = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : null;

        $results = [];
        if ($this->validate()) {
            $provisionalMembers = TblMemberProvisional::find()
                    ->andFilterWhere(['>=', 'cast(registration_date as date)', $fromDate])
                    ->andFilterWhere(['<=', 'cast(registration_date as date)', $toDate])
                    ->andFilterWhere(['=', 'union_code', $this->union_code])
                    ->all();
            $approvedProvisionalMembers = array_filter($provisionalMembers, function($member) {
                return strtolower($member->provisional_status) == 'approve';
            });
            $inprogressProvisionalMembers = array_filter($provisionalMembers, function($member) {
                return strtolower($member->provisional_status) == 'inprogress';
            });
            $pendingProvisionalMembers = array_filter($provisionalMembers, function($member) {
                return strtolower($member->provisional_status) == 'pending' || strtolower($member->provisional_status) == 'reroute';
            });
            $registeredProvisionalMembers = array_filter($provisionalMembers, function($member) {
                return strtolower($member->provisional_status) == 'register';
            });
            $rejectedProvisionalMembers = array_filter($provisionalMembers, function($member) {
                return strtolower($member->provisional_status) == 'reject';
            });
            $results[] = [
                'process_name' => Yii::t('app', 'Provisional Member'),
                'table_name' => 'tbl_member_provisional',
                'approved_count' => count($approvedProvisionalMembers),
                'inprogress_count' => count($inprogressProvisionalMembers),
                'pending_count' => count($pendingProvisionalMembers),
                'registered_count' => count($registeredProvisionalMembers),
                'rejected_count' => count($rejectedProvisionalMembers),
                'total_count' => count($provisionalMembers),
            ];

            $provisionalSocieties = TblDcsProvisional::find()
                    ->andFilterWhere(['>=', 'cast(registration_date as date)', $fromDate])
                    ->andFilterWhere(['<=', 'cast(registration_date as date)', $toDate])
                    ->andFilterWhere(['=', 'union_code', $this->union_code])
                    ->all();
            $approvedProvisionalSocieties = array_filter($provisionalSocieties, function($society) {
                return strtolower($society->status) == 'approve';
            });
            $inprogressProvisionalSocieties = array_filter($provisionalSocieties, function($society) {
                return strtolower($society->status) == 'inprogress';
            });
            $pendingProvisionalSocieties = array_filter($provisionalSocieties, function($society) {
                return strtolower($society->status) == 'pending' || strtolower($society->status) == 'reroute';
            });
            $registerProvisionalSocieties = array_filter($provisionalSocieties, function($society) {
                return strtolower($society->status) == 'register';
            });
            $rejectedProvisionalSocieties = array_filter($provisionalSocieties, function($society) {
                return strtolower($society->status) == 'reject';
            });
            $results[] = [
                'process_name' => Yii::t('app', 'Provisional Society'),
                'table_name' => 'tbl_dcs_provisional',
                'approved_count' => count($approvedProvisionalSocieties),
                'inprogress_count' => count($inprogressProvisionalSocieties),
                'pending_count' => count($pendingProvisionalSocieties),
                'registered_count' => count($registerProvisionalSocieties),
                'rejected_count' => count($rejectedProvisionalSocieties),
                'total_count' => count($provisionalSocieties),
            ];

            $provisionalVendors = TblCustomerMasterProvisional::find()
                    ->andFilterWhere(['>=', 'cast(created_at as date)', $fromDate])
                    ->andFilterWhere(['<=', 'cast(created_at as date)', $toDate])
                    ->andFilterWhere(['=', 'union_code', $this->union_code])
                    ->all();
            $approvedProvisionalVendors = array_filter($provisionalVendors, function($vendor) {
                return strtolower($vendor->status) == 'approve';
            });
            $inprogressProvisionalVendors = array_filter($provisionalVendors, function($vendor) {
                return strtolower($vendor->status) == 'inprogress';
            });
            $pendingProvisionalVendors = array_filter($provisionalVendors, function($vendor) {
                return strtolower($vendor->status) == 'pending' || strtolower($vendor->status) == 'reroute';
            });
            $registerProvisionalVendors = array_filter($provisionalVendors, function($vendor) {
                return strtolower($vendor->status) == 'register';
            });
            $rejectedProvisionalVendors = array_filter($provisionalVendors, function($vendor) {
                return strtolower($vendor->status) == 'reject';
            });
            $results[] = [
                'process_name' => Yii::t('app', 'Provisional Vendor/Customer'),
                'table_name' => 'tbl_customer_master_provisional',
                'approved_count' => count($approvedProvisionalVendors),
                'inprogress_count' => count($inprogressProvisionalVendors),
                'pending_count' => count($pendingProvisionalVendors),
                'registered_count' => count($registerProvisionalVendors),
                'rejected_count' => count($rejectedProvisionalVendors),
                'total_count' => count($provisionalVendors),
            ];
        }

        return new ArrayDataProvider([
            'allModels' => $results,
            'pagination' => false
        ]);
    }

}
