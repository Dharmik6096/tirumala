<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblGrn;
use app\modules\product\models\TblGrnTxn;

/**
 * TblGrnSearch represents the model behind the search form about `app\modules\product\models\TblGrn`.
 */
class TblGrnSearch extends TblGrn {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['grn_code', 'grn_no', 'grn_date', 'vendor_master_code', 'mcc_plant_code', 'invoice_date', 'invoice_no', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['originating_type'], 'integer'],
                [['from_date', 'to_date'], 'safe'],
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
        $query = TblGrn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['vendorCode', 'mccPlantCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_grn', 'tbl_mcc_plant');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->grn_date)) {
            $query->andFilterWhere(['like', 'cast(tbl_grn.grn_date as date)', date('Y-m-d', strtotime($this->grn_date))]);
        }
        if (!empty($this->invoice_date)) {
            $query->andFilterWhere(['like', 'cast(tbl_grn.invoice_date as date)', date('Y-m-d', strtotime($this->invoice_date))]);
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_grn.grn_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_grn.grn_date as date)', $to_date]);
        }
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'cast(tbl_grn.created_at as date)', date('Y-m-d', strtotime($this->created_at))]);
        }
        // grid filtering conditions

        $query->andFilterWhere(['like', 'grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'tbl_vendor_master.vendor_name', $this->vendor_master_code])
                ->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
