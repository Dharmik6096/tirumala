<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetDetail;

/**
 * TblAssetDetailSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetDetail`.
 */
class TblAssetDetailSearch extends TblAssetDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_detail_code', 'is_active'], 'integer'],
            [['asset_group_code', 'asset_code', 'store_location_code', 'serial_number', 'manufacturer_code', 'capacity', 'purchase_date', 'put_to_use_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['warranty_period', 'maintanance_duration_in_days'], 'number'],
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
        $query = TblAssetDetail::find();

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

        $query->joinWith(['assetGroupCode', 'assetCode', 'manufacturerCode', 'storeLocCode']);
        if (!empty($this->purchase_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), purchase_date, 126)', date('Y-m-d', strtotime($this->purchase_date))]);
        if (!empty($this->put_to_use_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), put_to_use_date, 126)', date('Y-m-d', strtotime($this->put_to_use_date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'asset_detail_code' => $this->asset_detail_code,
            'warranty_period' => $this->warranty_period,
            'maintanance_duration_in_days' => $this->maintanance_duration_in_days,
        ]);

        $query->andFilterWhere(['like', 'tbl_asset_group.asset_group_name', $this->asset_group_code])
                ->andFilterWhere(['like', 'tbl_asset_master.asset_name', $this->asset_code])
                ->andFilterWhere(['like', 'tbl_store_location.store_location_name', $this->store_location_code])
                ->andFilterWhere(['like', 'tbl_manufacturer.manufacturer_name', $this->manufacturer_code]);

        return $dataProvider;
    }

}
