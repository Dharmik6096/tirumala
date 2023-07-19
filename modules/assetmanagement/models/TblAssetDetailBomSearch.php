<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetDetailBom;

/**
 * TblAssetDetailBomSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetDetailBom`.
 */
class TblAssetDetailBomSearch extends TblAssetDetailBom {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_detail_bom_code', 'asset_detail_code', 'qty', 'is_active', 'originating_type'], 'integer'],
            [['spare_code', 'serial_number', 'union_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblAssetDetailBom::find();

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

        $query->joinWith(['spareCode']);
        // grid filtering conditions
        $query->andFilterWhere([
            'asset_detail_bom_code' => $this->asset_detail_bom_code,
            'asset_detail_code' => $this->asset_detail_code,
            'qty' => $this->qty,
            'is_active' => $this->is_active,
            'originating_type' => $this->originating_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_asset_master.asset_name', $this->spare_code])
                ->andFilterWhere(['like', 'serial_number', $this->serial_number])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
