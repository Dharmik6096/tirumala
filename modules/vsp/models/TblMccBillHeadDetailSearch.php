<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblMccBillHeadDetail;

/**
 * TblMccBillHeadDetailSearch represents the model behind the search form about `app\modules\vsp\models\TblMccBillHeadDetail`.
 */
class TblMccBillHeadDetailSearch extends TblMccBillHeadDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_bill_head_detail_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'mcc_bill_head_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['payment_cycle_code', 'is_installment', 'no_installment', 'is_active', 'originating_type'], 'integer'],
            [['amount'], 'number'],
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
        $query = TblMccBillHeadDetail::find();

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
            'payment_cycle_code' => $this->payment_cycle_code,
            'amount' => $this->amount,
            'is_installment' => $this->is_installment,
            'no_installment' => $this->no_installment,
            'is_active' => $this->is_active,
            'originating_type' => $this->originating_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'mcc_bill_head_detail_code', $this->mcc_bill_head_detail_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'mcc_bill_head_code', $this->mcc_bill_head_code])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblMccBillHeadDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['installmentCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this);

        $query->andWhere(['tbl_mcc_bill_head_detail.bmc_code' => $this->bmc_code]);

        $query->orderBy(['tbl_mcc_bill_head_installment.installment_date' => SORT_DESC, 'tbl_mcc_bill_head_detail.bmc_code' => SORT_ASC]);
        return $dataProvider;
    }

    public function installmentsearch($params) {
        $this->load($params);
        $query = TblMccBillHeadInstallment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_bill_head_installment');
        $query->andWhere([
            'mcc_bill_head_detail_code' => $this->mcc_bill_head_detail_code
        ]);
        return $dataProvider;
    }

}
