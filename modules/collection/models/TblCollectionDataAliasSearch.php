<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\general\models\TblProcessApproval;

/**
 * TblCollectionDataAliasSearch represents the model behind the search form about `app\modules\collection\models\TblCollectionDataAlias`.
 */
class TblCollectionDataAliasSearch extends TblCollectionDataAlias {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['collection_data_alias_code', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'sample_no', 'qty_mode', 'no_of_can', 'qlty_auto', 'qty_auto', 'converted_qty_mode', 'send_status', 'collection_type', 'doc_no', 'dispatch_type', 'destination_type', 'old_no_of_can', 'old_purchase_rate_code', 'originating_type'], 'integer'],
                [['table_name', 'action_perform', 'member_code', 'dcs_code', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'name', 'mobile_no', 'type_of_data_receive', 'purchase_rate_code', 'qlty_time', 'qty_time', 'route_code', 'remarks', 'sync_status', 'transporter_code', 'date_time_of_testing', 'vehicle_no', 'route_arrival_time', 'challan_no', 'destination_code', 'vehicle_in_time', 'vehicle_out_time', 'old_milk_type_code', 'old_milk_quality_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['fat', 'snf', 'clr', 'water', 'qty', 'rtpl', 'amount', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'converted_can', 'temperature', 'old_qty', 'old_fat', 'old_snf', 'old_rtpl', 'old_clr', 'old_amount'], 'number'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift', 'converted_amount', 'approved_at', 'approved_by', 'approval_status', 'vehicle_code'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['approvalCollection']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['approvalDispatch']],
                [['union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['approvalQtyImport']],
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
    public function search($params, $pending_approval = false) {
        $query = TblCollectionDataAlias::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel($this->table_name);
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_collection_data_alias.collection_data_alias_code) = convert(varchar(max),ap.process_code)')
                    ->addSelect(['tbl_collection_data_alias.*', 'ap.process_approval_code as process_approval_code'])
                    ->where(['tbl_collection_data_alias.approval_status' => ['Pending', 'Inprogress']]);
        }
//        $query->joinWith(['mccPlantCode.plantCode']);
        // grid filtering conditions
        $query->andWhere([
            'tbl_collection_data_alias.union_code' => $this->union_code,
            'tbl_collection_data_alias.plant_code' => $this->plant_code,
            'tbl_collection_data_alias.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_collection_data_alias.bmc_code' => $this->bmc_code,
            'tbl_collection_data_alias.table_name' => $this->table_name]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        if (!empty($this->member_code)) {
            $query->andFilterWhere(['member_code' => $this->member_code]);
        }
        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code]);
        $query->andFilterWhere(['like', 'customer_code', $this->customer_code]);
        $query->andFilterWhere(['like', 'customer_type', $this->customer_type]);
        $query->andFilterWhere(['like', 'action_perform', $this->action_perform]);
        return $dataProvider;
    }

    public function qtyimportsearch($params) {
        $query = TblCollectionDataAlias::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        $query->joinWith(['mccPlantCode.plantCode']);
        // grid filtering conditions
        $query->andWhere([
            'tbl_collection_data_alias.union_code' => $this->union_code,
            'tbl_collection_data_alias.plant_code' => $this->plant_code,
            'tbl_collection_data_alias.action_perform' => $this->action_perform,
            'tbl_collection_data_alias.table_name' => $this->table_name]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        $query->andFilterWhere([
            'member_code' => $this->member_code,
            'tbl_collection_data_alias.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_collection_data_alias.bmc_code' => $this->bmc_code,
            'dcs_code' => $this->dcs_code]);
        return $dataProvider;
    }

}
