<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblAppLockConfig;

/**
 * TblAppLockConfigSearch represents the model behind the search form about `app\modules\configuration\models\TblAppLockConfig`.
 */
class TblAppLockConfigSearch extends TblAppLockConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_code'], 'integer'],
            [['config_name', 'config_key', 'config_for'], 'safe'],
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
        $query = TblAppLockConfig::find();

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
}
