<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblAssetVerificationData;

/**
 * TblAssetVerificationDataSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblAssetVerificationData`.
 */
class TblAssetVerificationDataSearch extends TblAssetVerificationData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_verification_code', 'is_verified', 'originating_type'], 'integer'],
            [['asset_verification_code', 'asset_group_code', 'asset_code', 'serial_number', 'manufacturer_serial_number', 'is_verified', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'operation_type', 'history_created_at', 'history_created_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params){
        $query = TblAssetVerificationData::find();

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

        $query->joinWith(['assetGroupCode', 'assetCode']);

        $query->andFilterWhere(['like', 'tbl_asset_group.asset_group_name', $this->asset_group_code])
            ->andFilterWhere(['like', 'tbl_asset_master.asset_name', $this->asset_code]);

        return $dataProvider;
    }
}
