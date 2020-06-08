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

    public $mobile_no, $federation_code, $ifsc, $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_code', 'is_active', 'payment_mode', 'caste_category_code', 'member_type_code', 'bank_account_no', 'mobile_no', 'created_at', 'gender_code', 'milk_quality_type_code', 'ifsc', 'animal_type_code', 'member_name', 'nominee_name', 'pincode', 'updated_at', 'bank_code', 'branch_code', 'created_by', 'dcs_code', 'district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_center_code', 'sub_district_code', 'union_code', 'updated_by', 'village_code', 'email', 'is_download', 'download_date_time', 'reference_code', 'plant_code', 'mcc_plant_code','bmc_code'], 'safe'],
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
    
    public function searchApprovalData($params) {
        $query = TblMemberProvisional::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        $query->joinWith(['memberTypeCode', 'dcsCode']);        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this);
        
         $query->andWhere(['tbl_member_provisional.is_approved' => 0]);
         $query->andWhere(['tbl_member_provisional.bmc_code' => $this->bmc_code]);
        // var_dump($query->all());
        // die;
        $query->andFilterWhere(['like', 'tbl_member_provisional.dcs_code', $this->dcs_code]);

        return $dataProvider;

    }


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
        ]);



        $query->andFilterWhere(['like', 'tbl_member_provisional.member_code', $this->member_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member_provisional.member_name', $this->member_name])
                ->andFilterWhere(['like', 'tbl_member_provisional.mobile_no', $this->mobile_no]);

        return $dataProvider;
    }

}
