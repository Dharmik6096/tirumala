<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetSet;

/**
 * TblAssetSetSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetSet`.
 */
class TblAssetSetSearch extends TblAssetSet {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_set_code', 'status', 'is_active'], 'integer'],
            [['sap_code', 'sloc_code', 'store_location_type', 'reference_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'store_location_code'], 'safe'],
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
        $query = TblAssetSet::find()->where(['status' => [-1, 0, 2]]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        $query->joinWith(['storeLocCode.storeLocType']);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_asset_set.status' => $this->status,
            'tbl_asset_set.store_location_type' => $this->store_location_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_asset_set.sap_code', $this->sap_code])
                ->andFilterWhere(['like', 'tbl_asset_set.sloc_code', $this->sloc_code])
                ->andFilterWhere(['like', 'tbl_asset_set.reference_code', $this->reference_code])
                ->andFilterWhere(['like', 'tbl_asset_set.store_location_code', $this->store_location_code])
                ->andFilterWhere(['like', 'tbl_asset_set.sloc_code', $this->sloc_code])
//                ->andFilterWhere(['like', 'tbl_store_location_type.slt_name', $this->store_location_type])
                ->andFilterWhere(['like', 'tbl_asset_set.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_asset_set.asset_set_code', $this->asset_set_code]);

        return $dataProvider;
    }

}
