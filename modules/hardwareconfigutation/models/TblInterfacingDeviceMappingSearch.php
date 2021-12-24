<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hardwareconfigutation\models\TblInterfacingDeviceMapping;

/**
 * TblInterfacingDeviceMappingSearch represents the model behind the search form about `app\modules\hardwareconfigutation\models\TblInterfacingDeviceMapping`.
 */
class TblInterfacingDeviceMappingSearch extends TblInterfacingDeviceMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['interfacing_device_mapping_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'weight_device_code', 'analyzer_device_code', 'printer_device_code', 'display_device_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblInterfacingDeviceMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'weightDeviceCode', 'analyzerDeviceCode', 'printerDeviceCode', 'displayDeviceCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_interfacing_device_mapping', 'tbl_interfacing_device_mapping', 'tbl_interfacing_device_mapping');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_code])
                ->andFilterWhere(['like', 'tbl_interfacing_device.device_name', $this->weight_device_code])
                ->andFilterWhere(['like', 'tbl_interfacing_device.device_name', $this->analyzer_device_code])
                ->andFilterWhere(['like', 'tbl_interfacing_device.device_name', $this->printer_device_code])
                ->andFilterWhere(['like', 'tbl_interfacing_device.device_name', $this->display_device_code]);


        return $dataProvider;
    }

}
