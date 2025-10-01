<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetMaster;

/**
 * TblAssetMasterSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetMaster`.
 */
class TblAssetMasterSearch extends TblAssetMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_code', 'asset_group_code', 'asset_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name', 'cmpl_product_code'], 'safe'],
            [['is_serial_number', 'is_active'], 'integer'],
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
        $query = TblAssetMaster::find();

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
        $query->joinWith(['assetGroupCode', 'assetType']);
        // grid filtering conditions
        $query->andFilterWhere(['like', 'asset_code', $this->asset_code])
                ->andFilterWhere(['like', 'tbl_asset_group.asset_group_name', $this->asset_group_code])
                ->andFilterWhere(['like', 'tbl_complain_product.cmpl_product_name', $this->cmpl_product_code])
                ->andFilterWhere(['like', 'asset_name', $this->asset_name]);


        return $dataProvider;
    }

}
