<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblOrganizationLatlong;

/**
 * TblOrganizationLatlongSearch represents the model behind the search form about `app\modules\organisation\models\TblOrganizationLatlong`.
 */
class TblOrganizationLatlongSearch extends TblOrganizationLatlong
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_latlong_code', 'is_active', 'originating_type'], 'integer'],
            [['union_code', 'customer_type', 'customer_code', 'lat_long', 'address', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code'], 'safe'],
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
        $query = TblOrganizationLatlong::find();
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'customerCode', 'userCode']);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'organization_latlong_code' => $this->organization_latlong_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'lat_long', $this->lat_long]);

        return $dataProvider;
    }
}
