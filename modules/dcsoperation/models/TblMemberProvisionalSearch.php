<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberProvisional;

/**
 * TblMemberProvisionalSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMember`.
 */
class TblMemberProvisionalSearch extends TblMemberProvisional {

    public $mobile_no, $federation_code, $ifsc;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_code', 'is_active', 'payment_mode', 'caste_category_code', 'member_type_code', 'bank_account_no', 'mobile_no', 'created_at', 'gender_code', 'milk_quality_type_code', 'ifsc', 'animal_type_code', 'member_name', 'nominee_name', 'pincode', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'dcs_code', 'district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_center_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'email', 'is_download', 'download_date_time', 'reference_code'], 'safe'],
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
        $query = TblMemberProvisional::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['memberTypeCode', 'dcsCode']);        
        
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
            'tbl_member_provisional.is_active' => $this->is_active,
            'tbl_member_provisional.payment_mode' => $this->payment_mode,
            'tbl_member_provisional.caste_category_code' => $this->caste_category_code,
//            'milk_quality_type_code' => $this->milk_quality_type_code,
        ]);



        $query->andFilterWhere(['like', 'tbl_member_provisional.member_code', $this->member_code])
                //->andFilterWhere(['like', 'tbl_member_provisional.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.ex_member_code', $this->ex_member_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.member_name', $this->member_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.father_name', $this->father_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.surname', $this->surname])
                ->andFilterWhere(['like', 'tbl_member_provisional.nominee_name', $this->nominee_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.dob', $this->dob])
                //->andFilterWhere(['like', 'land_class', $this->land_class])
                ->andFilterWhere(['like', 'tbl_member_provisional.total_land', $this->total_land])
                ->andFilterWhere(['like', 'tbl_member_provisional.bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'tbl_member_provisional.ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'tbl_member_provisional.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_member_provisional.address', $this->address])
                ->andFilterWhere(['like', 'tbl_member_provisional.pincode', $this->pincode])
                ->andFilterWhere(['like', 'tbl_member_provisional.pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'tbl_member_provisional.adhar_no', $this->adhar_no])
                ->andFilterWhere(['like', 'tbl_member_provisional.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.created_by', $this->created_by])
                ->andFilterWhere(['like', 'tbl_member_provisional.updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'tbl_member_provisional.hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.district_code', $this->district_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.state_code', $this->state_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.local_father_name', $this->local_father_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.local_surname', $this->local_surname])
                ->andFilterWhere(['like', 'tbl_member_provisional.local_nominee_name', $this->local_nominee_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.is_download', $this->is_download])
                ->andFilterWhere(['like', 'tbl_member_provisional.local_address', $this->local_address])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', substr($this->reference_code,0,3)])
                ->andFilterWhere(['like', 'RIGHT(tbl_member_provisional.member_code,4)', substr($this->reference_code,3,4)]);

        return $dataProvider;
    }

    public function spSearch($params) {
        $this->load($params);
        $union_code = isset($params['TblMemberProvisionalSearch']['union_code']) ? $params['TblMemberProvisionalSearch']['union_code'] : NULL;
        $dcs_code = isset($params['TblMemberProvisionalSearch']['dcs_code']) ? $params['TblMemberProvisionalSearch']['dcs_code'] : NULL;
        $member_code = !empty($this->member_code) ? $this->member_code : NULL;
        $result = \Yii::$app->db->createCommand("{CALL [sp_member_list](:union_code,:dcs_code)}")
                ->bindValue(':union_code', $union_code)
                ->bindValue(':dcs_code', $dcs_code);
        $query = $result->queryAll();

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'modelClass' => 'app\modules\dcsoperation\models\TblMemberProvisional',
        ]);     
        return $dataProvider;
    }

}
