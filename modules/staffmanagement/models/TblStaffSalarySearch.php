<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffSalary;

/**
 * TblStaffSalarySearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffSalary`.
 */
class TblStaffSalarySearch extends TblStaffSalary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_salary_code', 'created_at', 'deleted_at', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'updated_at', 'wef_date', 'created_by', 'dcs_code', 'deleted_by', 'staff_member_code', 'sub_center_code', 'updated_by'], 'safe'],
            [['is_active', 'is_delete', 'net_pay'], 'integer'],
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
        $query = TblStaffSalary::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['subCenterCode','dcsCode','staffMemberCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'tbl_staff_salary.is_active' => $this->is_active,
            'tbl_staff_salary.is_delete' => $this->is_delete,
            'net_pay' => $this->net_pay,
            'sync_timestamp' => $this->sync_timestamp,
            'updated_at' => $this->updated_at,
            'wef_date' => $this->wef_date,
        ]);

        $query->andFilterWhere(['like', 'staff_salary_code', $this->staff_salary_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->orFilterWhere(['like', 'tbl_staff_salary.dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_code])
            ->andFilterWhere(['like', 'tbl_sub_center.sub_center_name', $this->sub_center_code]);

        return $dataProvider;
    }
}
