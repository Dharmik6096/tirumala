<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SystemConfiguration;

/**
 * SystemConfigurationSearch represents the model behind the search form about `app\models\SystemConfiguration`.
 */
class SystemConfigurationSearch extends SystemConfiguration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'from_value', 'to_value'], 'integer'],
            [['module_name', 'organization_type', 'organization_id'], 'safe'],
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
        $query = SystemConfiguration::find();

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
            'id' => $this->id,
            'from_value' => $this->from_value,
            'to_value' => $this->to_value,
        ]);

        $query->andFilterWhere(['like', 'module_name', $this->module_name])
            ->andFilterWhere(['like', 'organization_type', $this->organization_type])
            ->andFilterWhere(['like', 'organization_id', $this->organization_id]);

        return $dataProvider;
    }
}
