<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblDeviceMasterMapping;

/**
 * TblDeviceMasterMappingSearch represents the model behind the search form about `app\modules\globalmaster\models\TblDeviceMasterMapping`.
 */
class TblDeviceMasterMappingSearch extends TblDeviceMasterMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['device_mapping_code', 'device_master_code', 'applicability_code', 'applicability_type', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code'], 'safe'],
                [['originating_type'], 'integer'],
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
        $query = TblDeviceMasterMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['masterCode', 'bmcCode', 'dcsCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if (!empty($this->wef_date)) {
            $query->andFilterWhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->applicability_code], ['like', 'tbl_bmc.bmc_name', $this->applicability_code]]);

        $query->andFilterWhere(['like', 'device_mapping_code', $this->device_mapping_code])
                ->andFilterWhere(['like', 'device_master_code', $this->device_master_code])
                ->andFilterWhere(['like', 'tbl_master_Type.master_type', $this->applicability_type])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblDeviceMasterMapping::find();

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
            'device_master_code' => $this->device_master_code,
        ]);

        return $dataProvider;
    }

}
