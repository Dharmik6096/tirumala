<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffAdditionDeduction;

/**
 * TblStaffAdditionDeductionSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffAdditionDeduction`.
 */
class TblStaffAdditionDeductionSearch extends TblStaffAdditionDeduction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['installment_no', 'is_active', 'type'], 'integer'],
            [['amount'], 'number'],
            [['app_from_date', 'staff_member_name', 'created_at', 'remark', 'tr_date', 'updated_at', 'created_by', 'staff_member_code', 'union_code', 'updated_by'], 'safe'],
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
        $query = TblStaffAdditionDeduction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['staffMemberCode']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_staff_addition_deduction.is_active' => $this->is_active,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
        ]);
        if (!empty($this->tr_date))
            $query->andFilterWhere(['and', ['>=', 'tr_date', date('Y-m-d', strtotime($this->tr_date))], ['<=', 'tr_date', date('Y-m-d', strtotime($this->tr_date))]]);

        $query->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'installment_no', $this->installment_no])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['=', 'MONTH(app_from_date)', (!empty($this->app_from_date)) ? substr($this->app_from_date, 0, 2) : ''])
                ->andFilterWhere(['=', 'YEAR(app_from_date)', (!empty($this->app_from_date)) ? substr($this->app_from_date, 3, 4) : ''])
                ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_code]);

        return $dataProvider;
    }

}
