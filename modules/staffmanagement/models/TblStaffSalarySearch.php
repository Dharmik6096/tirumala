<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffSalary;

/**
 * TblStaffSalarySearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffSalary`.
 */
class TblStaffSalarySearch extends TblStaffSalary {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['total_value', 'staff_salary_code', 'created_at', 'addition', 'deduction', 'union_code', 'updated_at', 'wef_date', 'created_by', 'dcs_code', 'staff_member_code', 'updated_by'], 'safe'],
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
        $query = TblStaffSalary::find();

        // add conditions that should always apply here
        $query->orderBy(['wef_date' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['staffMemberCode']);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'staff_salary_code', $this->staff_salary_code])
                ->andFilterWhere(['=', 'MONTH(wef_date)', (!empty($this->wef_date)) ? substr($this->wef_date, 0, 2) : ''])
                ->andFilterWhere(['=', 'YEAR(wef_date)', (!empty($this->wef_date)) ? substr($this->wef_date, 3, 4) : ''])
                ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_code])
                ->andFilterWhere(['like', 'addition', $this->addition])
                ->andFilterWhere(['like', 'deduction', $this->deduction])
                ->andFilterWhere(['like', '(deduction - addition)', $this->total_value]);

        return $dataProvider;
    }

}
