<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetTransaction;

/**
 * TblAssetTransactionSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetTransaction`.
 */
class TblAssetTransactionSearch extends TblAssetTransaction {

    public $is_search;
    public $to_plant, $to_mcc, $to_bmc, $to_dcs, $sloc_code;
    public $cluster_email, $cluster_mobile, $vendor_email, $vendor_mobile;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_transaction_code', 'asset_detail_code', 'status', 'current_status'], 'integer'],
            [['from_type', 'from_dest', 'to_type', 'to_dest', 'asset_code', 'serial_number', 'union_code', 'received_date', 'received_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_search', 'put_to_use_date', 'purchase_date', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'make', 'sap_code', 'detail_code', 'manufacturer_serial_number', 'to_plant', 'to_mcc', 'to_bmc', 'to_dcs', 'sloc_code', 'cluster_email', 'cluster_mobile', 'vendor_email', 'vendor_mobile'], 'safe'],
            [['to_plant', 'to_mcc', 'to_bmc', 'to_dcs'], 'required', 'on'=> 'assetTransfer']
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
        $this->load($params);
        $query = TblAssetTransaction::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        if (!$this->validate() || empty($this->is_search)) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['assetCode', 'toStoreLocCode']);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['tbl_asset_transaction.current_status' => $this->current_status]);
        if (Yii::$app->session->get('UserType') == 7) {
            $query->andFilterWhere(['tbl_store_location.reference_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        } elseif (Yii::$app->session->get('UserType') == 5) {
            $query->andFilterWhere(['tbl_store_location.reference_code' => explode(',', Yii::$app->session->get('MCC'))]);
        } elseif (Yii::$app->session->get('UserType') == 4) {
            $query->andFilterWhere(['tbl_store_location.reference_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        $query->andFilterWhere(['tbl_store_location.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        // grid filtering conditions

        $query->andFilterWhere(['tbl_asset_master.asset_code' => $this->asset_code])
                ->andFilterWhere(['serial_number' => $this->serial_number])
                ->andFilterWhere(['manufacturer_serial_number' => $this->manufacturer_serial_number])
                ->andFilterWhere(['asset_detail_code' => $this->asset_detail_code]);

        return $dataProvider;
    }

    public function gridsearch($params) {
        $query = TblAssetTransaction::find();
        $query->where(['tbl_asset_transaction.status' => [0, -1, 2]]);
//        $query->where(['tbl_asset_transaction.current_status' => [0, 1, 2, 3]]);
//        $query->where(['or',
//            ['status' => [0, -1, 2]]
//        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        // Yii::$app->general->filterByOrg($query, $this, 'tbl_asset_transaction', 'tbl_dcs', 'tbl_dcs', 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['toStoreLocCode', 'toStoreLocCode.plantCode', 'toStoreLocCode.bmcCode', 'toStoreLocCode.dcsCode', 'assetDetail', 'assetCode', 'assetDetail.manufacturerCode', 'fromStoreLocCode as fromStoreLocCode', 'assetClusterVendorInfo']);
        if (!empty($this->purchase_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_asset_detail.purchase_date, 126)', date('Y-m-d', strtotime($this->purchase_date))]);
        if (!empty($this->put_to_use_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_asset_transaction.put_to_use_date, 126)', date('Y-m-d', strtotime($this->put_to_use_date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_asset_detail.warranty_period' => $this->warranty_period,
            'tbl_asset_detail.maintanance_duration_in_days' => $this->maintanance_duration_in_days,
            'tbl_asset_detail.current_status' => $this->current_status,
        ]);

        $query->andFilterWhere([
            'tbl_asset_transaction.status' => $this->status,
        ]);
        if (Yii::$app->session->get('UserType') == 7) {
            $query->andFilterWhere(['tbl_dcs.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        } elseif (Yii::$app->session->get('UserType') == 6) {
            $query->andFilterWhere(['or', ['tbl_bmc.bmc_code' => explode(',', Yii::$app->session->get('BMC'))], ['tbl_mcc_plant.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]]);
        } elseif (Yii::$app->session->get('UserType') == 4) {
            $query->andFilterWhere(['or', ['tbl_plant.plant_code' => explode(',', Yii::$app->session->get('Plant'))], ['tbl_mcc_plant.plant_code' => explode(',', Yii::$app->session->get('Plant'))], ['tbl_plant.plant_code' => explode(',', Yii::$app->session->get('Plant'))]]);
        }
        if (!empty($this->f_dcs_code)) {
            $query->andFilterWhere(['tbl_dcs.dcs_code' => $this->f_dcs_code]);
        } else if (!empty($this->f_bmc_code)) {
            $query->andFilterWhere(['tbl_bmc.bmc_code' => $this->f_bmc_code]);
        } else if (!empty($this->f_plant_code)) {
            $query->andFilterWhere(['tbl_plant.plant_code' => $this->f_plant_code]);
        }

        $query->andFilterWhere([
            'or',
            ['like', 'tbl_customer_master.customer_name', $this->from_dest],
            ['like', 'fromStoreLocCode.store_location_name', $this->from_dest],
        ]);


        $query->andFilterWhere(['tbl_asset_transaction.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        $query->andFilterWhere(['like', 'tbl_asset_master.asset_name', $this->asset_code])
                ->andFilterWhere(['like', 'tbl_asset_detail.make', $this->make])
                ->andFilterWhere(['like', 'tbl_asset_transaction.sap_code', $this->sap_code])
                ->andFilterWhere(['like', 'tbl_store_location.sloc_code', $this->sloc_code])
                ->andFilterWhere(['like', 'tbl_asset_transaction.serial_number', $this->serial_number])
                ->andFilterWhere(['like', 'tbl_asset_cluster_vendor_info.cluster_email', $this->cluster_email])
                ->andFilterWhere(['like', 'tbl_asset_cluster_vendor_info.cluster_mobile', $this->cluster_mobile])
                ->andFilterWhere(['like', 'tbl_asset_cluster_vendor_info.vendor_email', $this->vendor_email])
                ->andFilterWhere(['like', 'tbl_asset_cluster_vendor_info.vendor_mobile', $this->vendor_mobile]);

        return $dataProvider;
    }

    public function assetTranferSearch($params) {
        $query = TblAssetTransaction::find();
        $query->where(['tbl_asset_transaction.status' => [0, 2], 'to_type' => '3']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $storeLocationCode = TblStoreLocation::find()->select('store_location_code')->where(['reference_code' => $this->to_dcs, 'union_code' => $this->union_code,'store_location_type' => '3','is_active' => '1'])->scalar();
        if(!empty($storeLocationCode)){
            $this->to_dest = $storeLocationCode;
        } else{
            $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_asset_transaction.to_dest' => $this->to_dest,
        ]);

        $query->orderBy('tbl_asset_transaction.created_at DESC');

        return $dataProvider;
    }

}
