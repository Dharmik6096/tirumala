<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblDeviceConfigTemplate;

/**
 * TblDeviceConfigTemplateSearch represents the model behind the search form about `app\modules\configuration\models\TblDeviceConfigTemplate`.
 */
class TblDeviceConfigTemplateSearch extends TblDeviceConfigTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_temp_code', 'device_temp_name', 'created_at', 'created_by'], 'safe'],
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
        $query = TblDeviceConfigTemplate::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'device_temp_code', $this->device_temp_code])
            ->andFilterWhere(['like', 'device_temp_name', $this->device_temp_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
