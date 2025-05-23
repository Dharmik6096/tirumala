<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblGeneralPartyMaster;

/**
 * TblGeneralPartyMasterSearch represents the model behind the search form about `app\modules\product\models\TblGeneralPartyMaster`.
 */
class TblGeneralPartyMasterSearch extends TblGeneralPartyMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['general_party_master_code', 'is_product_sale', 'is_active', 'originating_type'], 'integer'],
            [['party_name', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ref_code', 'party_type', 'party_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
    public function search($params)
    {
        $query = TblGeneralPartyMaster::find();

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

        Yii::$app->general->filterByOrg($query, $this, 'tbl_general_party_master', 'tbl_general_party_master', 'tbl_general_party_master');
        // grid filtering conditions
        $query->andFilterWhere([
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'party_name', $this->party_name])
            ->andFilterWhere(['like', 'ref_code', $this->ref_code])
            ->andFilterWhere(['like', 'party_type', $this->party_type])
            ->andFilterWhere(['like', 'party_code', $this->party_code]);

        return $dataProvider;
    }
}
