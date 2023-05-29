<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblPlantDispatch;
use app\modules\product\models\TblPlantDispatchTxn;

/**
 * TblPlantDispatchSearch represents the model behind the search form about `app\modules\product\models\TblPlantDispatch`.
 */
class TblPlantDispatchSearch extends TblPlantDispatch {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_dispatch_code', 'dispatch_date', 'mcc_plant_code', 'plant_code', 'union_code', 'document_date', 'document_no', 'status', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblPlantDispatch::find();

        $request = Yii::$app->request->queryParams;
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
        $query->joinWith(['mccPlantCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_plant_dispatch', 'tbl_mcc_plant');

        // grid filtering conditions
        if (!empty($this->document_date)) {
            $query->andFilterWhere(['like', 'cast(tbl_plant_dispatch.document_date as date)', date('Y-m-d', strtotime($this->document_date))]);
        }
        if (!empty($this->dispatch_date)) {
            $query->andFilterWhere(['like', 'cast(tbl_plant_dispatch.dispatch_date as date)', date('Y-m-d', strtotime($this->dispatch_date))]);
        }
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'cast(tbl_plant_dispatch.created_at as date)', date('Y-m-d', strtotime($this->created_at))]);
        }
        $query->andFilterWhere(['like', 'plant_dispatch_code', $this->plant_dispatch_code])
                ->andFilterWhere(['like', 'document_no', $this->document_no])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblPlantDispatchTxn::find();

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
        $query->andWhere([
            'plant_dispatch_code' => $this->plant_dispatch_code,
        ]);



        return $dataProvider;
    }

    public function docnosearch($params) {
        $query = TblPlantDispatchTxn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->join('INNER JOIN', 'tbl_plant_dispatch', 'tbl_plant_dispatch.plant_dispatch_code=tbl_plant_dispatch_txn.plant_dispatch_code');

        $query->andWhere(['tbl_plant_dispatch.document_no' => $this->document_no]);
        $query->andWhere(['>', 'ISNULL(tbl_plant_dispatch_txn.grn_missing_qty,0)', 0]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        return $dataProvider;
    }

}
