<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblRawFgMaterialReceipt;

/**
 * TblRawFgMaterialReceiptSearch represents the model behind the search form about `app\modules\tankermovement\models\TblRawFgMaterialReceipt`.
 */
class TblRawFgMaterialReceiptSearch extends TblRawFgMaterialReceipt {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_date', 'to_date', 'union_code', 'plant_code', 'gross_weight', 'tare_weight', 'material_code', 'originating_type', 'raw_fg_material_receipt_code', 'receipt_datetime', 'party_code', 'vehicle_code', 'document_type', 'document_date', 'document_no', 'gross_weight_datetime', 'tare_weight_datetime', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblRawFgMaterialReceipt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_raw_fg_material_receipt', 'tbl_raw_fg_material_receipt');
        $query->joinWith(['partyMaster', 'vehicleCode', 'materialCode']);

        if (!empty($this->receipt_datetime)) {
            $query->andFilterWhere(['like', 'cast(tbl_raw_fg_material_receipt.receipt_datetime as date)', date('Y-m-d', strtotime($this->receipt_datetime))]);
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'tbl_raw_fg_material_receipt.receipt_datetime', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'tbl_raw_fg_material_receipt.receipt_datetime', $to_date]);
        }
        if (!empty($this->document_date)) {
            $query->andFilterWhere(['like', 'cast(tbl_raw_fg_material_receipt.document_date as date)', date('Y-m-d', strtotime($this->document_date))]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_raw_fg_material_receipt.gross_weight' => $this->gross_weight,
            'tbl_raw_fg_material_receipt.tare_weight' => $this->tare_weight,
        ]);

        $query->andFilterWhere(['like', 'tbl_party_master.party_name', $this->party_code])
                ->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->vehicle_code])
                ->andFilterWhere(['like', 'tbl_material_master.material_name', $this->material_code])
                ->andFilterWhere(['like', 'tbl_raw_fg_material_receipt.document_type', $this->document_type])
                ->andFilterWhere(['like', 'tbl_raw_fg_material_receipt.document_no', $this->document_no]);

        return $dataProvider;
    }

}
