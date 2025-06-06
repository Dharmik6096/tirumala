<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkTransfer;

/**
 * TblMilkTransferSearch represents the model behind the search form about `app\modules\collection\models\TblMilkTransfer`.
 */
class TblMilkTransferSearch extends TblMilkTransfer {

    public $transfer_types;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_transfer_code', 'transaction_id', 'from_date', 'to_date', 'union_code', 'source_code', 'destination_code', 'vehicle_no', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'transfer_types', 'transaction_datetime', 'shift_code'], 'safe'],
                [['from_shift', 'to_shift', 'transfer_type', 'originating_type'], 'integer'],
                [['fat', 'snf', 'qty', 'temp'], 'number'],
                [['transfer_types'], 'required', 'on' => ['indexSearch']],
                [['conductivity', 'ph_value', 'other_reading', 'freezing_point', 'salt', 'adt_value', 'adt_param', 'lactose', 'density', 'protein', 'water', 'clr', 'source_type', 'destination_type'], 'safe'],
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
        $query = TblMilkTransfer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_transfer.transfer_type' => $this->transfer_types,
            'tbl_milk_transfer.source_type' => $this->source_type,
            'tbl_milk_transfer.destination_type' => $this->destination_type,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_transfer');
        if ($this->transfer_types == 1) {
            if (Yii::$app->session->get('BMC') !== '')
                $query->andFilterWhere(['tbl_milk_transfer.destination_code' => explode(',', Yii::$app->session->get('BMC'))]);
        } elseif ($this->transfer_types == 0) {
            if (Yii::$app->session->get('BMC') !== '')
                $query->andFilterWhere(['tbl_milk_transfer.source_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (!empty($this->from_date))
            $query->andFilterWhere(['CAST(tbl_milk_transfer.from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['CAST(tbl_milk_transfer.to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);

        $query->andFilterWhere(['like', 'tbl_milk_transfer.transaction_id', $this->transaction_id])
                ->andFilterWhere(['like', 'tbl_milk_transfer.source_code', $this->source_code])
                ->andFilterWhere(['like', 'tbl_milk_transfer.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_milk_transfer.vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'tbl_milk_transfer.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_milk_transfer.fat', $this->fat])
                ->andFilterWhere(['like', 'tbl_milk_transfer.snf', $this->snf])
                ->andFilterWhere(['like', 'tbl_milk_transfer.qty', $this->qty])
                ->andFilterWhere(['like', 'tbl_milk_transfer.temp', $this->temp]);

        return $dataProvider;
    }

}
