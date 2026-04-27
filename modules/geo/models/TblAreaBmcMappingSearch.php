<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblAreaBmcMapping;

/**
 * TblAreaBmcMappingSearch represents the model behind the search form about `app\modules\geo\models\TblAreaBmcMapping`.
 */
class TblAreaBmcMappingSearch extends TblAreaBmcMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['area_bmc_mapping_code', 'is_active'], 'integer'],
            [['area_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'applicable_type', 'applicable_code'], 'safe'],
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
        $query = TblAreaBmcMapping::find();

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
            'area_code' => $this->area_code,
            'bmc_code' => $this->bmc_code,
            'applicable_type' => $this->applicable_type,
            'applicable_code' => $this->applicable_code,
        ]);

        return $dataProvider;
    }

}
