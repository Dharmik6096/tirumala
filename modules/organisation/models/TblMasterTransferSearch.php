<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblMasterTransfer;

/**
 * TblMasterTransferSearch represents the model behind the search form about `app\modules\organisation\models\TblMasterTransfer`.
 */
class TblMasterTransferSearch extends TblMasterTransfer {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['status'], 'integer'],
            [['master_type', 'transfer_type', 'old_member_code', 'new_member_code', 'old_dcs_code', 'new_dcs_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'old_route_code', 'new_route_code', 'plant_code', 'union_code', 'wef_date', 'pick_datetime', 'response_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblMasterTransfer::find();
        $query->joinWith(['requestType']);
        $query->leftJoin('tbl_mcc_plant om', 'om.mcc_plant_code=tbl_master_transfer.old_mcc_plant_code');
        $query->leftJoin('tbl_mcc_plant nm', 'nm.mcc_plant_code=tbl_master_transfer.new_mcc_plant_code');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_master_transfer', 'tbl_master_transfer');

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
            'status' => $this->status,
            'pick_datetime' => $this->pick_datetime,
            'response_datetime' => $this->response_datetime,
        ]);

        $query->andFilterWhere(['like', 'tbl_transfer_type.master_type_text', $this->master_type])
                ->andFilterWhere(['like', 'tbl_transfer_type.transfer_type_text', $this->transfer_type])
                ->andFilterWhere(['like', 'old_member_code', $this->old_member_code])
                ->andFilterWhere(['like', 'new_member_code', $this->new_member_code])
                ->andFilterWhere(['like', 'old_dcs_code', $this->old_dcs_code])
                ->andFilterWhere(['like', 'new_dcs_code', $this->new_dcs_code])
                ->andFilterWhere(['like', 'old_bmc_code', $this->old_bmc_code])
                ->andFilterWhere(['like', 'new_bmc_code', $this->new_bmc_code])
                ->andFilterWhere(['like', 'om.sloc_code', $this->old_mcc_plant_code])
                ->andFilterWhere(['like', 'nm.sloc_code', $this->new_mcc_plant_code])
                ->andFilterWhere(['like', 'old_route_code', $this->old_route_code])
                ->andFilterWhere(['like', 'new_route_code', $this->new_route_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
