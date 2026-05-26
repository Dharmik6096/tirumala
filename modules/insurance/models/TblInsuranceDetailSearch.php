<?php

namespace app\modules\insurance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\insurance\models\TblInsuranceDetail;
use yii\data\ArrayDataProvider;

/**
 * TblInsuranceDetailSearch represents the model behind the search form about `app\modules\insurance\models\TblInsuranceDetail`.
 */
class TblInsuranceDetailSearch extends TblInsuranceDetail {

    public $from_date, $to_date, $f_insurance_status;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['insurance_detail_code', 'insurance_master_code', 'age', 'is_delete', 'originating_type', 'sys_updated_by'], 'safe'],
            [['sr_no', 'union_code', 'plant_code', 'bmc_code', 'mcc_plant_code', 'dcs_code', 'dcs_name', 'member_id', 'member_code', 'member_name', 'adhar_no', 'dob', 'gender_code', 'nominee_adhar_no', 'nominee_member_name', 'mobile_no', 'date_of_joining_scheme', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'f_insurance_status'], 'safe'],
            [['insurance_master_code'], 'required'],
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
        $query = TblInsuranceDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'insuranceMasterCode']);
        $query->where(['tbl_insurance_detail.is_delete' => 0]);
        $query->andFilterWhere([
            'tbl_insurance_detail.union_code' => $this->f_union_code,
            'tbl_insurance_detail.plant_code' => $this->f_plant_code,
            'tbl_insurance_detail.mcc_plant_code' => $this->f_mcc_code,
            'tbl_insurance_detail.bmc_code' => $this->f_bmc_code,
            'tbl_insurance_detail.dcs_code' => $this->f_dcs_code,
        ]);

        $query->andFilterWhere(['or',
            ['tbl_dcs.dcs_code_ex' => $this->dcs_code],
            ['tbl_insurance_detail.dcs_code' => $this->dcs_code],
        ]);
        // $query->andFilterWhere(['or', []'tbl_dcs.dcs_code_ex' => $this->dcs_code,]);
        if (!empty($this->date_of_joining_scheme)) {
            $date_of_joining_scheme = date('Y-m-d', strtotime($this->date_of_joining_scheme));
            $query->andFilterWhere([
                'tbl_insurance_detail.date_of_joining_scheme' => $date_of_joining_scheme
            ]);
        }
        if (!empty($this->dob)) {
            $dob = date('Y-m-d', strtotime($this->dob));
            $query->andWhere([
                'tbl_insurance_detail.dob' => \Yii::$app->general->encryptData($dob),
            ]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_insurance_detail.insurance_detail_code' => $this->insurance_detail_code,
            'tbl_insurance_detail.insurance_master_code' => $this->insurance_master_code,
            'tbl_insurance_detail.age' => $this->age,
            'tbl_insurance_detail.status' => $this->f_insurance_status
        ]);

        $query->andFilterWhere(['like', 'tbl_insurance_detail.sr_no', $this->sr_no])
                ->andFilterWhere(['like', 'tbl_insurance_detail.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_insurance_detail.member_id', $this->member_id])
                ->andFilterWhere(['like', 'RIGHT(tbl_insurance_detail.member_code, 4)', $this->member_code])
                ->andFilterWhere(['like', 'tbl_insurance_detail.member_name', $this->member_name])
                ->andFilterWhere(['like', 'tbl_insurance_detail.adhar_no', $this->adhar_no])
                ->andFilterWhere(['like', 'tbl_insurance_detail.gender_code', $this->gender_code])
                ->andFilterWhere(['like', 'tbl_insurance_detail.nominee_adhar_no', $this->nominee_adhar_no])
                ->andFilterWhere(['like', 'tbl_insurance_detail.nominee_member_name', $this->nominee_member_name])
                ->andFilterWhere(['like', 'tbl_insurance_detail.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_insurance_detail.status', $this->status]);

        return $dataProvider;
    }

    public function searchSummary($params) {
        $this->load($params);

        $query = TblInsuranceDetail::find()->select(['tbl_insurance_detail.insurance_master_code', 'tbl_insurance_detail.status', 'tbl_insurance_detail.dcs_code', 'tbl_insurance_detail_summary.dcs_name', 'member_count' => 'count(member_code)', 'tbl_insurance_detail_summary.to_date', 'tbl_insurance_detail_summary.from_date', 'tbl_dcs.dcs_code_ex']);
        $query->joinWith(['dcsCode', 'insuranceDetailSummaryCode']);
        $query->where(['tbl_insurance_detail.insurance_master_code' => $this->insurance_master_code]);
        $query->andWhere(['is_delete' => 0]);
        $query->groupBy(['tbl_insurance_detail.insurance_master_code', 'tbl_insurance_detail.status', 'tbl_insurance_detail.dcs_code', 'tbl_insurance_detail_summary.dcs_name', 'tbl_insurance_detail_summary.to_date', 'tbl_insurance_detail_summary.from_date', 'dcs_code_ex']);
        $data = $query->asArray()->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
        return $dataProvider;
    }
}
