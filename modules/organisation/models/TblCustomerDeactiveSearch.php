<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblCustomerDeactive;

/**
 * TblCustomerDeactiveSearch represents the model behind the search form about `app\modules\organisation\models\TblCustomerDeactive`.
 */
class TblCustomerDeactiveSearch extends TblCustomerDeactive {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_deactive_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'from_date', 'to_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'from_date', 'to_date'], 'safe'],
            [['originating_type', 'data_post_status'], 'integer'],
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
        $query = TblCustomerDeactive::find()->where(['IS', 'to_date', NULL]);

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
        Yii::$app->general->filterByOrg($query, $this, 'tbl_customer_deactive', 'tbl_customer_deactive', 'tbl_customer_deactive');
        if (!empty($this->from_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_customer_deactive.from_date', date('Y-m-d', strtotime($this->from_date))], ['<=', 'tbl_customer_deactive.from_date', date('Y-m-d', strtotime($this->from_date))]]);

        // grid filtering conditions
        $query->andFilterWhere([
            'customer_deactive_code' => $this->customer_deactive_code,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblCustomerDeactive::find();

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
            'customer_code' => $this->customer_code,
            'customer_type' => $this->customer_type,
        ]);

        return $dataProvider;
    }

}
