<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblInventoryTransfer;

/**
 * TblInventoryTransferSearch represents the model behind the search form about `app\modules\product\models\TblInventoryTransfer`.
 */
class TblInventoryTransferSearch extends TblInventoryTransfer {

    public $from_date, $to_date, $from_name, $to_name, $f_plant_code, $f_mcc_code, $f_dcs_code, $f_bmc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['inventory_transfer_code', 'inventory_transfer_no', 'inventory_transfer_date', 'from_type', 'from_code', 'to_type', 'to_code', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['originating_type'], 'integer'],
                [['from_date', 'to_date', 'from_name', 'to_name', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'f_plant_code'], 'safe']
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
        $query = TblInventoryTransfer::find();

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

        if (!empty(Yii::$app->session->get('Unions'))) {
            $query->andWhere(['tbl_inventory_transfer.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (!empty($this->from_code)) {
            $query->joinWith(['dcsFromCode', 'mccFromCode', 'bmcFromCode', 'dcsToCode', 'mccToCode', 'bmcToCode']);
            $query->andFilterWhere(['or', ['like', 'tbl_mcc_plant.ref_code', $this->from_code], ['like', 'tbl_dcs.ref_code', $this->from_code], ['like', 'tbl_bmc.ref_code', $this->from_code]]);
        }
        if (!empty($this->from_name)) {
            $query->joinWith(['dcsFromCode', 'mccFromCode', 'bmcFromCode']);
            $query->andFilterWhere(['or', ['like', 'tbl_mcc_plant.name', $this->from_name], ['like', 'tbl_dcs.dcs_name', $this->from_name], ['like', 'tbl_bmc.bmc_name', $this->from_name]]);
        }
        // grid filtering conditions
        if (!empty($this->inventory_transfer_date)) {
            $query->andFilterWhere(['and', ['>=', 'tbl_inventory_transfer.inventory_transfer_date', date('Y-m-d', strtotime($this->inventory_transfer_date))], ['<=', 'inventory_transfer_date', date('Y-m-d', strtotime($this->inventory_transfer_date))]]);
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'tbl_inventory_transfer.inventory_transfer_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'tbl_inventory_transfer.inventory_transfer_date', $to_date]);
        }
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'cast(tbl_inventory_transfer.created_at as date)', date('Y-m-d', strtotime($this->created_at))]);
        }
        if (!empty($this->f_dcs_code)) {
            $query->andFilterWhere(['like', 'tbl_inventory_transfer.to_code', $this->f_dcs_code]);
        } elseif (!empty($this->f_bmc_code)) {
            $query->andFilterWhere(['like', 'tbl_inventory_transfer.to_code', $this->f_bmc_code]);
        } elseif (!empty($this->f_mcc_code)) {
            $query->andFilterWhere(['like', 'tbl_inventory_transfer.to_code', $this->f_mcc_code]);
        }

        $query->andFilterWhere(['like', 'inventory_transfer_code', $this->inventory_transfer_code])
                ->andFilterWhere(['like', 'inventory_transfer_no', $this->inventory_transfer_no])
                ->andFilterWhere(['like', 'from_type', $this->from_type])
                ->andFilterWhere(['like', 'to_type', $this->to_type])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'union_code', $this->f_union_code])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
