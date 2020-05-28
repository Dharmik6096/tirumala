<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffMember;
use app\modules\staffmanagement\models\TblStaffMemberDesignation;

/**
 * TblStaffMemberSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffMember`.
 */
class TblStaffMemberSearch extends TblStaffMember {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_member_code', 'aadhar_card_no', 'address', 'bank_account_no', 'birth_date', 'designation_code', 'created_at', 'email_id', 'ifsc', 'mobile_no', 'pan_no', 'pincode', 'staff_member_name', 'tenure_from_date', 'tenure_to_date', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'updated_by', 'village_code', 'union_code', 'is_on_role'], 'safe'],
            [['is_active', 'payment_mode', 'blood_group_code', 'caste_category_code', 'gender_code'], 'integer'],
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
        $query = TblStaffMember::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['designationCode']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'birth_date' => $this->birth_date,
            'tbl_staff_member.is_active' => $this->is_active,
            'payment_mode' => $this->payment_mode,
            'tenure_to_date' => $this->tenure_to_date,
            'updated_at' => $this->updated_at,
            'blood_group_code' => $this->blood_group_code,
            'caste_category_code' => $this->caste_category_code,
            'gender_code' => $this->gender_code,
            'is_on_role' => $this->is_on_role,
        ]);
        if (!empty($this->tenure_from_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_staff_member.tenure_from_date', date('Y-m-d', strtotime($this->tenure_from_date))], ['<=', 'tbl_staff_member.tenure_from_date', date('Y-m-d', strtotime($this->tenure_from_date))]]);

        $query->andFilterWhere(['like', 'aadhar_card_no', $this->aadhar_card_no])
                ->andFilterWhere(['like', 'tbl_designation.designation_name', $this->designation_code])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'email_id', $this->email_id])
                ->andFilterWhere(['like', 'ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'pincode', $this->pincode])
                ->andFilterWhere(['like', 'staff_member_name', $this->staff_member_name])
                ->andFilterWhere(['like', 'bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'district_code', $this->district_code])
                ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'state_code', $this->state_code])
                ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'village_code', $this->village_code]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblStaffMemberDesignation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere(['staff_member_code' => $this->staff_member_code]);

        return $dataProvider;
    }

}
