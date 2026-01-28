<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\dcsoperation\models\TblBiplSmart;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class TblBiplSmartSearch extends TblBiplSmart {

    public $f_mcc_plant_code, $from_date, $to_date, $bipl_type;

    public function rules() {
        return [
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'f_dcs_code', 'bipl_type', 'data_post_status', 'from_date', 'to_date'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_plant_code', 'f_bmc_code', 'bipl_type'], 'required'],
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
                    ->select([
                        'dcs.dcs_code AS code',
                        'u.union_name as union_name',
                        'dcs.dcs_code_ex as ex_code',
                        'dcs.dcs_code AS dcs_code',
                        'dcs.dcs_name AS name',
                        'dcs.sap_vendor_code AS sap_vendor_code',
                        'dcs.ref_code AS dcs_ref_code',
                        'bmc.ref_code AS bmc_ref_code',
                        'route.ref_code AS route_ref_code',
                        'route.sap_route_code',
                        'contact.mobile_no as mobile_no',
                        'dcs.is_active',
                        'dcs.valid_from as effective_date',
                        'dcs.data_post_status as data_post_status',
                        'dcs.picked_datetime as picked_datetime',
                        'dcs.response_datetime as response_datetime',
                        'dcs.resp_status as resp_status',
                        'dcs.resp_desc as resp_desc',
                    ])
                    ->leftJoin('tbl_bmc bmc', 'dcs.bmc_code = bmc.bmc_code')
                    ->leftJoin('tbl_route_mapping route', 'dcs.route_code = route.route_code')
                    ->leftJoin('tbl_contact_details contact', "contact.module_code = dcs.dcs_code AND contact.module_name = 'society'")
                    ->leftJoin('tbl_unions u', 'dcs.union_code = u.union_code')
                    ->where(['dcs.is_active' => 1]);
            $tablePrefix = 'dcs';
        } else {
            $query = TblMember::find()->alias('member')
                    ->select([
                        'member.member_code AS code',
                        'member.member_code AS member_code',
                        'u.union_name as union_name',
                        'member.ex_member_code as ex_code',
                        'member.dcs_code AS dcs_code',
                        'member.member_name AS name',
                        'member.surname AS last_name',
                        'member.sap_farmer_code AS sap_vendor_code',
                        'dcs.ref_code AS dcs_ref_code',
                        'bmc.ref_code AS bmc_ref_code',
                        'member.mobile_no as mobile_no',
                        'member.is_active',
                        'member.registration_date as effective_date',
                        'member.adhar_no',
                        'member.address',
                        'gender.gender',
                        'member.data_post_status as data_post_status',
                        'member.picked_datetime as picked_datetime',
                        'member.response_datetime as response_datetime',
                        'member.resp_status as resp_status',
                        'member.resp_desc as resp_desc',
                    ])
                    ->leftJoin('tbl_dcs dcs', 'member.dcs_code = dcs.dcs_code')
                    ->leftJoin('tbl_bmc bmc', 'dcs.bmc_code = bmc.bmc_code')
                    ->leftJoin('tbl_gender gender', 'member.gender_code = gender.gender_code')
                    ->leftJoin('tbl_unions u', 'dcs.union_code = u.union_code')
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
        Yii::$app->general->filterByOrg($query, $this, 'dcs', 'dcs', 'dcs', 'dcs');

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
