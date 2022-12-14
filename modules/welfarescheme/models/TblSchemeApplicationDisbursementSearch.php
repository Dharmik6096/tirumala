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
                [['from_date', 'to_date', 'union_code', 'scheme_id', 'disburse_value', 'disburse_id', 'application_id', 'originating_type', 'disburse_date', 'disburse_by', 'payment_mode', 'bank_name', 'branch_name', 'party_name', 'party_relation', 'payment_ref_id', 'payment_detail', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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

        $query->joinWith(['applicationId', 'relationshipId']);

        Yii::$app->general->filterByOrg($query, $this);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d', strtotime($this->disburse_date));
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
            'disburse_id' => $this->disburse_id,
            'union_code' => $this->union_code,
            'tbl_scheme_application_disbursement.scheme_id' => $this->scheme_id,
        ]);

        $query->andFilterWhere(['like', 'payment_mode', $this->payment_mode])
                ->andFilterWhere(['like', 'bank_name', $this->bank_name])
                ->andFilterWhere(['like', 'tbl_scheme_application.application_id', $this->application_id])
                ->andFilterWhere(['like', 'disburse_value', $this->disburse_value])
                ->andFilterWhere(['like', 'branch_name', $this->branch_name])
                ->andFilterWhere(['like', 'party_name', $this->party_name])
                ->andFilterWhere(['like', 'tbl_relationship.relationship', $this->party_relation])
                ->andFilterWhere(['like', 'payment_ref_id', $this->payment_ref_id])
                ->andFilterWhere(['like', 'payment_detail', $this->payment_detail])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
