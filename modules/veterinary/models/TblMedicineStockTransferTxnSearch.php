<?php

namespace app\modules\veterinary\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblMedicineStockTransferTxn;

/**
 * TblMedicineStockTransferTxnSearch represents the model behind the search form about `app\modules\veterinary\models\TblMedicineStockTransferTxn`.
 */
class TblMedicineStockTransferTxnSearch extends TblMedicineStockTransferTxn {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_user_code', 'to_user_code'], 'required'],
            [['from_user_code'], 'validateFromTo'],
            [['medicine_id', 'originating_type'], 'integer'],
            [['medicine_stock_transfer_code', 'union_code', 'batch_no', 'expire_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_user_code', 'to_user_code', 'medicine_wise', 'remarks', 'transaction_date', 'rate'], 'safe'],
            [['qty', 'available_stock'], 'number'],
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
        $query = TblMedicineStockTransferTxn::find();

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
            'medicine_stock_transfer_txn_code' => $this->medicine_stock_transfer_txn_code,
            'medicine_id' => $this->medicine_id,
            'qty' => $this->qty,
            'available_stock' => $this->available_stock,
            'expire_date' => !empty($this->expire_date) ? date('Y-m-d', strtotime($this->expire_date)) : NULL,
        ]);

        $query->andFilterWhere(['like', 'medicine_stock_transfer_code', $this->medicine_stock_transfer_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'batch_no', $this->batch_no]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblMedicineStockTransferTxn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andWhere([
            'medicine_stock_transfer_code' => $this->medicine_stock_transfer_code,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions

        return $dataProvider;
    }

    public function validateFromTo($attribute, $params) {
        if ($this->from_user_code == $this->to_user_code) {
            $this->addError($attribute, 'From User and To User cannot be the same.');
        }
    }

}
