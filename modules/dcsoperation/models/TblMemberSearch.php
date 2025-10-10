<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMember;

/**
 * TblMemberSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMember`.
 */
class TblMemberSearch extends TblMember {

    public $mobile_no, $federation_code, $ifsc;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_code', 'is_active', 'payment_mode', 'caste_category_code', 'member_type_code', 'bank_account_no', 'mobile_no', 'created_at', 'gender_code', 'milk_quality_type_code', 'ifsc', 'animal_type_code', 'member_name', 'nominee_name', 'pincode', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'dcs_code', 'district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_center_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'email', 'is_download', 'download_date_time', 'reference_code', 'rate_class', 'witness_name', 'place'], 'safe'],
                [['ex_member_code', 'ref_code', 'employee_code', 'employee_name', 'region_code', 'aadhaar_card_address', 'is_email_verify', 'email_relation', 'member_identity_no', 'applicant_relation', 'is_kyc_verified', 'sap_farmer_code'], 'safe'],
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
        $query = TblMember::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['memberTypeCode', 'dcsCode', 'regionCode', 'userName']);

        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->download_date_time))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), download_date_time, 126)', date('Y-m-d', strtotime($this->download_date_time))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_member.is_active' => $this->is_active,
            'tbl_member.payment_mode' => $this->payment_mode,
            'tbl_member.caste_category_code' => $this->caste_category_code,
            'tbl_member.rate_class' => $this->rate_class,
//            'milk_quality_type_code' => $this->milk_quality_type_code,
        ]);

        // grid filtering conditions
        /* $query->andFilterWhere([
          'bloodgroup_code' => $this->bloodgroup_code,
          'gender_code' => $this->gender_code,
          'qualification_code' => $this->qualification_code,
          'caste_category_code' => $this->caste_category_code,
          'no_of_buffalo' => $this->no_of_buffalo,
          'no_of_cow_cross' => $this->no_of_cow_cross,
          'no_of_cow_ind' => $this->no_of_cow_ind,
          'total_animals' => $this->total_animals,
          'member_type_code' => $this->member_type_code,
          'annual_income' => $this->annual_income,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
          'deleted_at' => $this->deleted_at,
          'is_active' => $this->is_active,
          'is_delete' => $this->is_delete,
          'payment_mode' => $this->payment_mode,
          'animal_type_code' => $this->animal_type_code,
          ]); */

        $query->andFilterWhere(['like', 'tbl_member.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_member.member_code', $this->member_code])
                //->andFilterWhere(['like', 'tbl_member.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member.ex_member_code', $this->ex_member_code])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_name])
                ->andFilterWhere(['like', 'tbl_member.father_name', $this->father_name])
                ->andFilterWhere(['like', 'tbl_member.surname', $this->surname])
                ->andFilterWhere(['like', 'tbl_member.nominee_name', $this->nominee_name])
                ->andFilterWhere(['like', 'tbl_member.dob', $this->dob])
                //->andFilterWhere(['like', 'land_class', $this->land_class])
                ->andFilterWhere(['like', 'tbl_member.total_land', $this->total_land])
                ->andFilterWhere(['like', 'tbl_member.bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'tbl_member.branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'tbl_member.bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'tbl_member.ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'tbl_member.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_member.address', $this->address])
                ->andFilterWhere(['like', 'tbl_member.pincode', $this->pincode])
                ->andFilterWhere(['like', 'tbl_member.pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'tbl_member.adhar_no', $this->adhar_no])
                ->andFilterWhere(['like', 'tbl_member.village_code', $this->village_code])
                ->andFilterWhere(['like', 'user.name', $this->created_by])
                ->andFilterWhere(['like', 'tbl_member.updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'tbl_member.hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'tbl_member.sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_member.district_code', $this->district_code])
                ->andFilterWhere(['like', 'tbl_member.state_code', $this->state_code])
                ->andFilterWhere(['like', 'tbl_member.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_member.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_member.local_father_name', $this->local_father_name])
                ->andFilterWhere(['like', 'tbl_member.local_surname', $this->local_surname])
                ->andFilterWhere(['like', 'tbl_member.local_nominee_name', $this->local_nominee_name])
                ->andFilterWhere(['like', 'tbl_member.is_download', $this->is_download])
                ->andFilterWhere(['like', 'tbl_member.local_address', $this->local_address])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', substr($this->reference_code, 0, 3)])
                ->andFilterWhere(['like', 'RIGHT(tbl_member.member_code,4)', substr($this->reference_code, 3, 4)])
                ->andFilterWhere(['like', 'tbl_member.employee_name', $this->employee_name])
                ->andFilterWhere(['like', 'tbl_member.employee_code', $this->employee_code])
                ->andFilterWhere(['like', 'tbl_region.region_name', $this->region_code])
                ->andFilterWhere(['like', 'tbl_member.sap_farmer_code', $this->sap_farmer_code]);

        return $dataProvider;
    }

    public function spSearch($params) {
        $this->load($params);
        $union_code = isset($params['TblMemberSearch']['union_code']) ? $params['TblMemberSearch']['union_code'] : NULL;
        $dcs_code = isset($params['TblMemberSearch']['dcs_code']) ? $params['TblMemberSearch']['dcs_code'] : NULL;
        $member_code = !empty($this->member_code) ? $this->member_code : NULL;
        $result = \Yii::$app->db->createCommand("{CALL [sp_member_list](:union_code,:dcs_code)}")
                ->bindValue(':union_code', $union_code)
                ->bindValue(':dcs_code', $dcs_code);
        $query = $result->queryAll();
//        $query = TblMember::find();
//        var_dump($query);die; 
//        var_dump($this->className());die;

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'modelClass' => 'app\modules\dcsoperation\models\TblMember',
        ]);
//        $query = TblMember::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//
//        $this->load($params);
//
//        $query->joinWith(['memberTypeCode']);
//
//        Yii::$app->general->filterByOrg($query,$this);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//        if (!empty($this->download_date_time))
//            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), download_date_time, 126)', date('Y-m-d', strtotime($this->download_date_time))]);
//
//        // grid filtering conditions
//        $query->andFilterWhere([
//            'tbl_member.is_active' => $this->is_active,
//            'tbl_member.payment_mode' => $this->payment_mode,
//            'caste_category_code' => $this->caste_category_code,
////            'milk_quality_type_code' => $this->milk_quality_type_code,
//        ]);
//
//        // grid filtering conditions
//        /* $query->andFilterWhere([
//          'bloodgroup_code' => $this->bloodgroup_code,
//          'gender_code' => $this->gender_code,
//          'qualification_code' => $this->qualification_code,
//          'caste_category_code' => $this->caste_category_code,
//          'no_of_buffalo' => $this->no_of_buffalo,
//          'no_of_cow_cross' => $this->no_of_cow_cross,
//          'no_of_cow_ind' => $this->no_of_cow_ind,
//          'total_animals' => $this->total_animals,
//          'member_type_code' => $this->member_type_code,
//          'annual_income' => $this->annual_income,
//          'created_at' => $this->created_at,
//          'updated_at' => $this->updated_at,
//          'deleted_at' => $this->deleted_at,
//          'is_active' => $this->is_active,
//          'is_delete' => $this->is_delete,
//          'payment_mode' => $this->payment_mode,
//          'animal_type_code' => $this->animal_type_code,
//        ]);*/
//
//        $query->andFilterWhere(['like', 'member_code', $this->member_code])
//                //->andFilterWhere(['like', 'tbl_member.dcs_code', $this->dcs_code])
//                ->andFilterWhere(['like', 'ex_member_code', $this->ex_member_code])
//                ->andFilterWhere(['like', 'member_name', $this->member_name])
//                ->andFilterWhere(['like', 'father_name', $this->father_name])
//                ->andFilterWhere(['like', 'surname', $this->surname])
//                ->andFilterWhere(['like', 'nominee_name', $this->nominee_name])
//                ->andFilterWhere(['like', 'dob', $this->dob])
//                //->andFilterWhere(['like', 'land_class', $this->land_class])
//                ->andFilterWhere(['like', 'total_land', $this->total_land])
//                ->andFilterWhere(['like', 'bank_code', $this->bank_code])
//                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
//                ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
//                ->andFilterWhere(['like', 'ifsc', $this->ifsc])
//                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
//                ->andFilterWhere(['like', 'address', $this->address])
//                ->andFilterWhere(['like', 'pincode', $this->pincode])
//                ->andFilterWhere(['like', 'pan_no', $this->pan_no])
//                ->andFilterWhere(['like', 'adhar_no', $this->adhar_no])
//                ->andFilterWhere(['like', 'village_code', $this->village_code])
//                ->andFilterWhere(['like', 'created_by', $this->created_by])
//                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
//                ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
//                ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
//                ->andFilterWhere(['like', 'district_code', $this->district_code])
//                ->andFilterWhere(['like', 'state_code', $this->state_code])
//                ->andFilterWhere(['like', 'tbl_member.union_code', $this->union_code])
//                ->andFilterWhere(['like', 'local_name', $this->local_name])
//                ->andFilterWhere(['like', 'local_father_name', $this->local_father_name])
//                ->andFilterWhere(['like', 'local_surname', $this->local_surname])
//                ->andFilterWhere(['like', 'local_nominee_name', $this->local_nominee_name])
//                ->andFilterWhere(['like', 'is_download', $this->is_download])
//                ->andFilterWhere(['like', 'local_address', $this->local_address]);

        return $dataProvider;
    }

}
