<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblConfig;

/**
 * TblConfigSearch represents the model behind the search form about `app\modules\configuration\models\TblConfig`.
 */
class TblConfigSearch extends TblConfig {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_code'], 'integer'],
            [['config_name', 'config_key', 'config_for', 'config_type', 'process_name'], 'safe'],
            [['config_for', 'process_name'], 'required']
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
        $query = TblConfig::find();

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
            'config_code' => $this->config_code,
        ]);

        $query->andFilterWhere(['like', 'config_name', $this->config_name])
                ->andFilterWhere(['like', 'config_key', $this->config_key])
                ->andFilterWhere(['like', 'config_for', $this->config_for]);

        return $dataProvider;
    }

    public function mappingsearch($params) {
        $query = TblConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'config_for' => $this->config_for,
            'process_name' => $this->process_name,
            'config_type' => $this->config_type,
            'ISNULL(is_input_config,0)' => 0
        ]);

        return $dataProvider;
    }

}
