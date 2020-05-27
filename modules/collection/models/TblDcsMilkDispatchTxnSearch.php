<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblDcsMilkDispatchTxn;

/**
 * TblDcsMilkDispatchTxnSearch represents the model behind the search form about `app\modules\collection\models\TblDcsMilkDispatchTxn`.
 */
class TblDcsMilkDispatchTxnSearch extends TblDcsMilkDispatchTxn {

    public $shift_code, $dispatch_type, $destination_type, $challan_no, $destination_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code', 'dispatch_type', 'destination_type', 'challan_no', 'destination_code'], 'safe'],
            [['dcs_milk_dispatch_txn_code', 'dcs_milk_dispatch_code', 'milk_quality_type_code', 'milk_type_code', 'nos_of_can', 'converted_qty_mode'], 'integer'],
            [['dispatch_qty', 'qty_mode', 'converted_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'water', 'temperature', 'total_amount'], 'number'],
            [['dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblDcsMilkDispatchTxn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsMilkDispatch']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_milk_dispatch', 'tbl_dcs_milk_dispatch', 'tbl_dcs_milk_dispatch');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_dispatch', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_dispatch', $to_date]);
        }
        if (!empty($this->date_time_of_dispatch))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_dispatch, 126)', date('Y-m-d', strtotime($this->date_time_of_dispatch))]);

        $query->andFilterWhere([
            'tbl_dcs_milk_dispatch.shift_code' => $this->shift_code,
            'tbl_dcs_milk_dispatch.dispatch_type' => $this->dispatch_type,
            'tbl_dcs_milk_dispatch.destination_type' => $this->destination_type,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type_code' => $this->milk_type_code,
            'nos_of_can' => $this->nos_of_can,
            'dispatch_qty' => $this->dispatch_qty,
            'qty_mode' => $this->qty_mode,
            'converted_qty' => $this->converted_qty,
            'converted_qty_mode' => $this->converted_qty_mode,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'avg_clr' => $this->avg_clr,
            'water' => $this->water,
            'total_amount' => $this->total_amount,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs_milk_dispatch.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_dcs_milk_dispatch.destination_code', $this->destination_code]);
        
        $query->orderBy(['tbl_dcs_milk_dispatch.date_time_of_dispatch' => SORT_DESC, 
        'tbl_dcs_milk_dispatch.bmc_code' => SORT_ASC, 
        'tbl_dcs_milk_dispatch.dcs_code' => SORT_ASC]);

        return $dataProvider;
    }

}
