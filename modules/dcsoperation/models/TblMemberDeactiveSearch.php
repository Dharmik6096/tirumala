<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberDeactive;

/**
 * TblMemberDeactiveSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberDeactive`.
 */
class TblMemberDeactiveSearch extends TblMemberDeactive {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_deactive_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'from_date', 'to_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['originating_type'], 'integer'],
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
        $query = TblMemberDeactive::find()->where(['IS', 'to_date', NULL]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_deactive', 'tbl_member_deactive', 'tbl_member_deactive');
        if (!empty($this->from_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_member_deactive.from_date', date('Y-m-d', strtotime($this->from_date))], ['<=', 'tbl_member_deactive.from_date', date('Y-m-d', strtotime($this->from_date))]]);

        // grid filtering conditions
        $query->andFilterWhere([
//            'from_date' => $this->from_date,
//            'to_date' => $this->to_date,
            'member_code' => $this->member_code,
            'member_deactive_code' => $this->member_deactive_code,
        ]);



        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblMemberDeactive::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'member_code' => $this->member_code,
        ]);

        return $dataProvider;
    }

}
