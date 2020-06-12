<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblConfigMapping;

/**
 * TblConfigMappingSearch represents the model behind the search form about `app\modules\configuration\models\TblConfigMapping`.
 */
class TblConfigMappingSearch extends TblConfigMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_mapping_code', 'config_code', 'originating_type'], 'integer'],
            [['config_result', 'org_type', 'org_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblConfigMapping::find()->select(['tbl_config_mapping.union_code', 'tbl_config_mapping.plant_code', 'tbl_config_mapping.mcc_plant_code', 'tbl_config_mapping.bmc_code', 'config_for' => 'tbl_config.config_for', 'process_name' => 'tbl_config.process_name'])->groupBy(['tbl_config_mapping.union_code', 'tbl_config_mapping.plant_code', 'tbl_config_mapping.mcc_plant_code', 'tbl_config_mapping.bmc_code', 'tbl_config.config_for', 'tbl_config.process_name']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['configCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_config_mapping', 'tbl_config_mapping', 'tbl_config_mapping');


        // grid filtering conditions
        $query->andFilterWhere([
            'config_mapping_code' => $this->config_mapping_code,
            'config_code' => $this->config_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'config_result', $this->config_result])
                ->andFilterWhere(['like', 'org_type', $this->org_type])
                ->andFilterWhere(['like', 'org_code', $this->org_code]);

        return $dataProvider;
    }

    public function detailsearch($params) {

        $query = TblConfigMapping::find();
        $query->andWhere(['union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name]);
        // add conditions that should always apply here
        $query->joinWith(['configCode']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
