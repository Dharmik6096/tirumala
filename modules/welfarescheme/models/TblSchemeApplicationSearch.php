<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApplication;

/**
 * TblSchemeApplicationSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApplication`.
 */
class TblSchemeApplicationSearch extends TblSchemeApplication {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['application_id', 'scheme_id', 'originating_type'], 'integer'],
                [['customer_code', 'customer_type', 'application_date', 'remarks', 'application_status', 'status_date', 'status_by', 'status_remarks', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['min_pouring_day', 'min_pouring_qty', 'actual_pouring_day', 'actual_pouring_qty', 'scheme_value', 'approved_value'], 'number'],
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
        $query = TblSchemeApplication::find();

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
            'application_id' => $this->application_id,
            'scheme_id' => $this->scheme_id,
            'application_date' => $this->application_date,
            'min_pouring_day' => $this->min_pouring_day,
            'min_pouring_qty' => $this->min_pouring_qty,
            'actual_pouring_day' => $this->actual_pouring_day,
            'actual_pouring_qty' => $this->actual_pouring_qty,
            'scheme_value' => $this->scheme_value,
            'approved_value' => $this->approved_value,
            'status_date' => $this->status_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'application_status', $this->application_status])
                ->andFilterWhere(['like', 'status_by', $this->status_by])
                ->andFilterWhere(['like', 'status_remarks', $this->status_remarks])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
