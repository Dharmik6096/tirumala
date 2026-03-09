<?php

namespace app\modules\organisation\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblOrganizationLatlong;

/**
 * TblOrganizationLatlongSearch represents the model behind the search form about `app\modules\organisation\models\TblOrganizationLatlong`.
 */
class TblOrganizationLatlongSearch extends TblOrganizationLatlong
{
    public $ref_code, $customer_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_latlong_code', 'is_active', 'originating_type'], 'integer'],
            [['union_code', 'customer_type', 'customer_code', 'lat_long', 'address', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'ref_code', 'customer_name'], 'safe'],
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

        $query->andFilterWhere(['like', 'tbl_organization_latlong.customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'tbl_organization_latlong.customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'lat_long', $this->lat_long]);

        if (!empty($this->ref_code)) {
            $query->andWhere(['or', ['like', 'tbl_organization_latlong.customer_code', $this->ref_code], ['like', 'tbl_dcs.ref_code', $this->ref_code], ['like', 'tbl_bmc.ref_code', $this->ref_code], ['like', 'tbl_plant.ref_code', $this->ref_code], ['like', 'tbl_mcc_plant.ref_code', $this->ref_code], ['like', 'tbl_customer_master.ref_code', $this->ref_code]]);
        }

        if (!empty($this->customer_name)) {
            $query->andWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_bmc.bmc_name', $this->customer_name], ['like', 'tbl_plant.plant_name', $this->customer_name], ['like', 'tbl_mcc_plant.mcc_plant_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
        }

        return $dataProvider;
    }
}
