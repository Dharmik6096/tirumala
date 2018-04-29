<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffSalaryProcessing;

/**
 * TblStaffSalaryProcessingSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffSalaryProcessing`.
 */
class TblStaffSalaryProcessingSearch extends TblStaffSalaryProcessing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['month', 'account_no', 'created_at', 'disbursement_date', 'flg_sentbox_entry', 'is_active', 'sync_status', 'sync_timestamp', 'updated_at', 'union_code', 'sub_center_code', 'staff_member_code', 'dcs_code', 'bank_code', 'branch_code', 'created_by', 'salary_head_code', 'updated_by','staff_member_name'], 'safe'],
            [['effective_working_days', 'is_delete', 'type_of_head', 'designation_code'], 'integer'],
            [['lwp', 'value'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblStaffSalaryProcessing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['staffMemberCode','bankCode','dcsCode']);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'disbursement_date' => $this->disbursement_date,
            'effective_working_days' => $this->effective_working_days,
            'tbl_staff_salary_processing.is_active' => $this->is_active,
            'tbl_staff_salary_processing.is_delete' => $this->is_delete,
            'lwp' => $this->lwp,
            'sync_timestamp' => $this->sync_timestamp,
            'type_of_head' => $this->type_of_head,
            'updated_at' => $this->updated_at,
            'value' => $this->value,
            'designation_code' => $this->designation_code,
        ]);

        $query->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'account_no', $this->account_no])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
            ->andFilterWhere(['like', 'sync_status', $this->sync_status])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'sub_center_code', $this->sub_center_code])
            ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_name])
            ->andFilterWhere(['like', 'tbl_staff_member.staff_member_code', $this->staff_member_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
            ->orFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_banks.bank_name', $this->bank_code])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'salary_head_code', $this->salary_head_code])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
