<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\dcsoperation\models\TblBiplSmart;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;

class TblBiplSmartSearch extends TblBiplSmart {

    public $f_mcc_plant_code, $from_date, $to_date, $bipl_type;

    public function rules() {
        return [
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'f_dcs_code', 'bipl_type', 'data_post_status', 'from_date', 'to_date'], 'safe'],
            [['bipl_type'], 'required'],
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code'], 'required', 'when' => function($model) {
                return $model->bipl_type != '2';
            }],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $this->load($params);
        $this->from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $this->to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');

        if ($this->bipl_type == '1') {
            $query = TblDcs::find()->alias('dcs')
                    ->select(['bmc.ref_code AS bmc_ref_code', 'dcs.ref_code AS dcs_ref_code', 'dcs_code', 'dcs_code_ex', 'dcs_name', 'dcs.sap_vendor_code', 'route.ref_code AS route_ref_code', 'route.sap_route_code', 'contact.mobile_no', 'dcs.is_active', 'dcs.valid_from', 'dcs.data_post_status', 'dcs.picked_datetime', 'dcs.response_datetime', 'dcs.resp_desc'])
                    ->leftJoin('tbl_bmc bmc', 'dcs.bmc_code = bmc.bmc_code')
                    ->leftJoin('tbl_route_mapping route', 'dcs.route_code = route.route_code')
                    ->leftJoin('tbl_contact_details contact', "contact.module_code = dcs.dcs_code AND contact.module_name = 'society'")
                    ->where(['dcs.is_active' => 1]);
            $tablePrefix = 'dcs';
        } elseif ($this->bipl_type == '2') {
            $query = TblPurchaseRate::find()->alias('rate')
                    ->select(['rate.purchase_rate_code', 'rate.wef_date', 's.shift', 'rt.rate_type', 'rate.description', 'rate.data_post_status', 'rate.picked_datetime', 'rate.response_datetime', 'rate.resp_desc'])
                    ->leftJoin('tbl_shift s', 'rate.shift_id = s.id')
                    ->leftJoin(['min_rde' => (new \yii\db\Query())
                        ->select(['purchase_rate_code', 'min_code' => 'MAX(code)'])
                        ->from('tbl_purchase_rate_details')
                        ->groupBy('purchase_rate_code')], 'rate.purchase_rate_code = min_rde.purchase_rate_code')
                    ->leftJoin(['rde' => 'tbl_purchase_rate_details'], 'rde.purchase_rate_code = min_rde.purchase_rate_code AND rde.code = min_rde.min_code')
                    ->leftJoin('tbl_rate_type rt', 'rde.rate_type_code = rt.code')
                    ->where(['rate.is_active' => 1]);
            $tablePrefix = 'rate';
        } elseif ($this->bipl_type == '3') {
            $query = TblPurchaseRateApplicability::find()->alias('rateapp')
                    ->select(['rateapp.purchase_rate_code', 'rateapp.rate_app_code', 'rateapp.dcs_code', 'rateapp.wef_date', 's.shift', 'rateapp.data_post_status', 'rateapp.picked_datetime', 'rateapp.response_datetime', 'rateapp.resp_desc'])
                    ->leftJoin('tbl_dcs dcs', 'rateapp.dcs_code = dcs.dcs_code')
                    ->leftJoin('tbl_shift s', 'rateapp.shift_code = s.id')
                    ->where(['rateapp.is_active' => 1]);
            $tablePrefix = 'rateapp';
        } else {
            $query = TblMember::find()->alias('member')
                    ->select(['bmc.ref_code AS bmc_ref_code', 'dcs.ref_code AS dcs_ref_code', 'member.dcs_code', 'member_code', 'ex_member_code', 'member_name', 'surname', 'sap_farmer_code', 'member.mobile_no', 'member.is_active', 'member.registration_date', 'adhar_no', 'member.address', 'gender.gender', 'member.data_post_status', 'member.picked_datetime', 'member.response_datetime', 'member.resp_desc'])
                    ->leftJoin('tbl_dcs dcs', 'member.dcs_code = dcs.dcs_code')
                    ->leftJoin('tbl_bmc bmc', 'dcs.bmc_code = bmc.bmc_code')
                    ->leftJoin('tbl_gender gender', 'member.gender_code = gender.gender_code')
                    ->where(['member.is_active' => 1]);
            $tablePrefix = 'member';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->asArray(),
            'pagination' => FALSE,
            'sort' => false
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        if ($this->bipl_type == '2') {
            $query->andFilterWhere(['rate.union_code' => $this->f_union_code]);
        } else {
            Yii::$app->general->filterByOrg($query, $this, 'dcs', 'dcs', 'dcs', 'dcs');
        }

        $query->andWhere([
            'OR',
            [
                'AND',
                ['>=', "CAST($tablePrefix.created_at as DATE)", $this->from_date],
                ['<=', "CAST($tablePrefix.created_at as DATE)", $this->to_date],
            ],
            [
                'AND',
                ['>=', "CAST($tablePrefix.updated_at as DATE)", $this->from_date],
                ['<=', "CAST($tablePrefix.updated_at as DATE)", $this->to_date],
            ],
        ]);

        $query->andFilterWhere(["$tablePrefix.data_post_status" => $this->data_post_status]);
        return $dataProvider;
    }

}
