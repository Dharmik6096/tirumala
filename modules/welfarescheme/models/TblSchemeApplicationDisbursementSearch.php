<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursement;

/**
 * TblSchemeApplicationDisbursementSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApplicationDisbursement`.
 */
class TblSchemeApplicationDisbursementSearch extends TblSchemeApplicationDisbursement {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['disburse_id', 'application_id', 'originating_type'], 'integer'],
                [['from_date', 'to_date', 'union_code', 'scheme_id', 'disburse_value', 'disburse_id', 'application_id', 'originating_type', 'disburse_date', 'disburse_by', 'payment_mode', 'bank_code', 'branch_code', 'beneficiary_name', 'party_relation', 'payment_ref_id', 'payment_detail', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'ifsc', 'bank_account_no'], 'safe'],
                [['disburse_value'], 'number'],
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
        $query = TblSchemeApplicationDisbursement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['applicationId', 'relationshipId', 'bankCode', 'branchCode']);

        Yii::$app->general->filterByOrg($query, $this);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : '';
        $selected_date = !empty($from_date) ? $from_date : (!empty($this->disburse_date) ? date('Y-m-d', strtotime($this->disburse_date)) : '');
        $query->andFilterWhere(['>=', 'tbl_scheme_application_disbursement.disburse_date', $from_date]);


        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'tbl_scheme_application_disbursement.disburse_date', $to_date]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_scheme_application_disbursement.union_code' => $this->union_code,
            'tbl_scheme_application_disbursement.scheme_id' => $this->scheme_id,
        ]);

        $query->andFilterWhere(['like', 'tbl_scheme_application_disbursement.payment_mode', $this->payment_mode])
                ->andFilterWhere(['like', 'tbl_banks.bank_name', $this->bank_code])
                ->andFilterWhere(['like', 'tbl_scheme_application.application_id', $this->application_id])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.disburse_value', $this->disburse_value])
                ->andFilterWhere(['like', 'tbl_branch.branch_name', $this->branch_code])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.beneficiary_name', $this->beneficiary_name])
                ->andFilterWhere(['like', 'tbl_relationship.relationship', $this->party_relation])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.payment_ref_id', $this->payment_ref_id])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.payment_detail', $this->payment_detail])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'tbl_scheme_application_disbursement.remarks', $this->remarks]);

        return $dataProvider;
    }

}
