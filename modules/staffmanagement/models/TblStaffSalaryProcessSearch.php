<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffSalaryProcess;

/**
 * TblStaffSalaryProcessSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffSalaryProcess`.
 */
class TblStaffSalaryProcessSearch extends TblStaffSalaryProcess {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['salary_code', 'staff_member_code', 'disbursement_date', 'month', 'account_no', 'bank_code', 'branch_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'previous_hold', 'previous_due', 'hold_amount', 'additional_pay'], 'safe'],
            [['actual_value', 'value', 'lwp'], 'number'],
            [['effective_working_days', 'designation_code', 'originating_type'], 'integer'],
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
        $query = TblStaffSalaryProcess::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'month' => $this->month,
            'union_code' => $this->union_code,
        ]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $query = TblStaffSalaryProcess::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['staffMemberCode', 'bankCode']);
        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'month', $this->month])
                ->andFilterWhere(['like', 'lwp', $this->lwp])
                ->andFilterWhere(['like', 'effective_working_days', $this->effective_working_days])
                ->andFilterWhere(['like', 'value', $this->value])
                ->andFilterWhere(['like', 'actual_value', $this->actual_value])
                ->andFilterWhere(['like', 'previous_hold', $this->previous_hold])
                ->andFilterWhere(['like', 'previous_due', $this->previous_due])
                ->andFilterWhere(['like', 'hold_amount', $this->hold_amount])
                ->andFilterWhere(['like', 'additional_pay', $this->additional_pay])
                ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_code])
                ->andFilterWhere(['like', 'tbl_banks.bank_name', $this->bank_code])
                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'disbursement_date', (!empty($this->disbursement_date)) ? date('Y-m-d', strtotime($this->disbursement_date)) : '']);

        return $dataProvider;
    }

}
