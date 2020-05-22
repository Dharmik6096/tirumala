<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffAttendance;

/**
 * TblStaffAttendanceSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffAttendance`.
 */
class TblStaffAttendanceSearch extends TblStaffAttendance {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lwp_type', 'salary_processed'], 'integer'],
            [['created_at', 'staff_member_name', 'lwp_date', 'remark', 'updated_at', 'created_by', 'staff_member_code', 'updated_by', 'union_code'], 'safe'],
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
        $query = TblStaffAttendance::find();

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
            'lwp_type' => $this->lwp_type,
            'salary_processed' => $this->salary_processed,
        ]);
        if (!empty($this->lwp_date))
            $query->andFilterWhere(['and', ['>=', 'lwp_date', date('Y-m-d', strtotime($this->lwp_date))], ['<=', 'lwp_date', date('Y-m-d', strtotime($this->lwp_date))]]);

        $query->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'tbl_staff_member.staff_member_name', $this->staff_member_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
